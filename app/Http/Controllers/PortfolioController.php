<?php

namespace App\Http\Controllers;

use App\Support\Portfolio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'projects' => Portfolio::entries('projects'),
            'clients' => Portfolio::entries('clients'),
            'testimonials' => Portfolio::entries('testimonials'),
        ]);
    }

    public function project(string $slug): View
    {
        $project = Portfolio::entries('projects')->firstWhere('slug', $slug);
        abort_unless($project, 404);

        return view('projects.show', compact('project'));
    }

    public function sitemap(): Response
    {
        $urls = collect([Portfolio::canonical()])->merge(
            Portfolio::entries('projects')->reject(fn ($p) => ($p['demo'] ?? false) || ($p['placeholder'] ?? false))
                ->map(fn ($p) => Portfolio::canonical('projects/'.$p['slug']))
        );

        return response()->view('seo.sitemap', compact('urls'))->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $rules = Portfolio::indexable() ? "Allow: /\nDisallow: /contact\n" : "Disallow: /\n";

        return response("User-agent: *\n".$rules.'Sitemap: '.Portfolio::canonical('sitemap.xml')."\n")
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
