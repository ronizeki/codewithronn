<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ProjectInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request): RedirectResponse
    {
        if (! config('portfolio.contact_form_enabled')) {
            return $this->failed($request);
        }
        $localMailer = in_array(config('mail.default'), ['log', 'array']);
        if (app()->environment('production') && $localMailer) {
            return $this->failed($request);
        }
        try {
            Mail::to(config('portfolio.email'))->send(new ProjectInquiry($request->safe()->except('website')));
        } catch (Throwable $exception) {
            Log::error('Contact delivery failed', ['exception_type' => $exception::class]);

            return $this->failed($request);
        }

        return redirect()->to(route('home').'#contact')->with('contact_success', $localMailer
            ? 'Demo mode: your message was recorded locally. No email was sent. Use WhatsApp to contact Roni directly.'
            : "Thank you! Your message has been received. I'll get back to you soon.")
            ->with('contact_delivered', ! $localMailer);
    }

    private function failed(ContactRequest $request): RedirectResponse
    {
        return redirect()->to(route('home').'#contact')->withInput($request->except('_token', 'website'))
            ->withErrors(['delivery' => 'Your message could not be sent right now. Please try again or contact me on WhatsApp.']);
    }
}
