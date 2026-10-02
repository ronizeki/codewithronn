<?php

namespace Tests\Feature;

use App\Mail\ProjectInquiry;
use App\Support\Portfolio;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    private function inquiry(array $overrides = []): array
    {
        return array_replace(['name' => 'Test Visitor', 'email' => 'visitor@example.com', 'phone' => '+62 812 3456', 'company' => 'Example business', 'project_type' => 'Web Application', 'budget' => 'Let’s discuss', 'message' => 'We need an application to manage our inventory and purchasing.', 'website' => ''], $overrides);
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['portfolio.contact_form_enabled' => true]);
    }

    public function test_home_only_shows_owner_data_and_direct_contact_without_smtp(): void
    {
        config(['portfolio.contact_form_enabled' => false, 'portfolio.projects' => []]);
        $response = $this->get('/')->assertOk()->assertSee('Roni Zeki')->assertSee('6+')
            ->assertDontSee('Nexora')->assertDontSee('Andi Pratama')->assertDontSee('20+')
            ->assertDontSee('10+')->assertDontSee('PROFILE PLACEHOLDER')->assertDontSee('Concept project')
            ->assertDontSee('id="projects"', false)->assertDontSee('id="contact-form"', false)
            ->assertSee('mailto:ronizeki83@gmail.com')->assertSee('https://wa.me/6285272339039');
        $html = $response->getContent();
        $this->assertSame(1, substr_count($html, '<h1'));
        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);
        $schema = json_decode($matches[1], true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Person', $schema['@graph'][0]['@type']);
        $this->assertStringNotContainsString('aggregateRating', $matches[1]);
    }

    public function test_removed_demo_case_studies_return_404(): void
    {
        foreach (['inventory-management-system', 'e-commerce-platform', 'logistics-tracking-dashboard', 'property-management-system', 'company-profile-lead-generation', 'internal-crm'] as $slug) {
            $this->get('/projects/'.$slug)->assertNotFound();
        }
    }

    public function test_editable_projects_and_contact_form_are_visible(): void
    {
        $response = $this->get('/')->assertOk()->assertSee('id="contact-form"', false)
            ->assertSee('name="_token"', false)->assertSee('id="projects"', false);
        $this->assertSame(6, substr_count($response->getContent(), 'class="project-card"'));
        foreach (config('portfolio.projects') as $project) {
            $this->get('/projects/'.$project['slug'])->assertOk()->assertSee($project['title'])
                ->assertSee('editable project layout')->assertSee('noindex, nofollow');
        }
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('project-01');
    }

    public function test_only_explicitly_published_non_demo_entries_can_be_exposed(): void
    {
        config(['portfolio.clients' => [
            ['name' => 'Draft record'],
            ['name' => 'Synthetic test fixture', 'published' => true, 'demo' => true],
            ['name' => 'Approved test fixture', 'published' => true],
        ]]);
        $this->assertSame(['Approved test fixture'], Portfolio::entries('clients')->pluck('name')->all());
    }

    public function test_sitemap_uses_canonical_and_only_published_records(): void
    {
        config(['app.url' => 'https://portfolio.example', 'portfolio.indexable' => true,
            'portfolio.projects' => [['slug' => 'approved-test-record', 'published' => true], ['slug' => 'draft-test-record']]]);
        $this->app->instance('env', 'production');
        $response = $this->get('/sitemap.xml')->assertOk()->assertDontSee('draft-test-record');
        $xml = simplexml_load_string($response->getContent());
        $this->assertCount(2, $xml->url);
        $this->assertSame('https://portfolio.example', (string) $xml->url[0]->loc);
        $this->get('/robots.txt')->assertOk()->assertSee('Allow: /')->assertSee('https://portfolio.example/sitemap.xml');
    }

    public function test_disabled_contact_form_cannot_send_mail(): void
    {
        config(['portfolio.contact_form_enabled' => false]);
        Mail::fake();
        $this->post('/contact', $this->inquiry())->assertSessionHasErrors('delivery');
        Mail::assertNothingSent();
    }

    public function test_valid_inquiry_is_sent_to_owner_with_visitor_reply_to(): void
    {
        Mail::fake();
        config(['mail.default' => 'smtp']);
        $this->post('/contact', $this->inquiry())->assertRedirect(route('home').'#contact')
            ->assertSessionHas('contact_delivered', true)->assertSessionHas('contact_success');
        Mail::assertSent(ProjectInquiry::class, function ($mail) {
            return $mail->hasTo(config('portfolio.email')) && $mail->envelope()->replyTo[0]->address === 'visitor@example.com';
        });
    }

    public function test_invalid_input_and_honeypot_never_send_mail(): void
    {
        Mail::fake();
        $this->post('/contact', $this->inquiry(['email' => 'invalid', 'project_type' => 'Unknown', 'budget' => 'Unknown', 'message' => 'Short', 'website' => 'spam.example']))
            ->assertSessionHasErrors(['email', 'project_type', 'budget', 'message', 'website']);
        Mail::assertNothingSent();
    }

    public function test_missing_required_fields_and_header_injection_are_rejected(): void
    {
        Mail::fake();
        $this->post('/contact', [])->assertSessionHasErrors(['name', 'email', 'project_type', 'message']);
        $this->post('/contact', $this->inquiry(['name' => "Name\r\nBcc: injected@example.com"]))->assertSessionHasErrors('name');
        Mail::assertNothingSent();
    }

    public function test_transport_failure_preserves_input_and_does_not_report_success(): void
    {
        config(['mail.default' => 'smtp']);
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('SMTP unavailable'));
        $this->post('/contact', $this->inquiry())->assertSessionHasErrors('delivery')
            ->assertSessionHas('_old_input.name', 'Test Visitor')->assertSessionMissing('contact_success');
    }

    public function test_local_mailer_is_honest_and_is_rejected_in_production(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        Mail::fake();
        config(['mail.default' => 'log']);
        $this->post('/contact', $this->inquiry())->assertSessionHas('contact_delivered', false)
            ->assertSessionHas('contact_success', fn ($message) => str_contains($message, 'No email was sent'));
        $this->app->instance('env', 'production');
        $this->post('/contact', $this->inquiry())->assertSessionHasErrors('delivery');
        Mail::assertSentCount(1);
    }

    public function test_sixth_submission_is_rate_limited(): void
    {
        Mail::fake();
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', $this->inquiry())->assertRedirect();
        }
        $this->post('/contact', $this->inquiry())->assertStatus(429)->assertSee('requests.');
        Mail::assertSentCount(5);
    }

    public function test_mail_content_is_escaped(): void
    {
        $html = (new ProjectInquiry($this->inquiry(['message' => '<script>alert(1)</script> business inquiry'])))->render();
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function test_csrf_protects_real_requests_without_token(): void
    {
        Mail::fake();
        $this->app->instance('env', 'local');
        $this->post('/contact', $this->inquiry())->assertStatus(419);
        Mail::assertNothingSent();
    }
}
