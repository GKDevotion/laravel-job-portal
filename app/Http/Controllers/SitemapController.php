<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobListing;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    /**
     * Generated fresh on every request rather than cached to a static file — job portal
     * listings change constantly (new jobs, closed jobs), and the volume here doesn't
     * justify the staleness tradeoff of a cron-generated static sitemap. Revisit with
     * caching (e.g. cache::remember for 1 hour) if the site grows past ~50k listings.
     */
    public function index(): Response
    {
        // All app routes are locale-prefixed (Phase 3), so sitemap URLs need the prefix too.
        // This generates one locale's URL set (the request's current locale, or the default) —
        // for full multi-language SEO, add <xhtml:link rel="alternate" hreflang="..."> entries
        // per locale here; kept simple for now since spatie/laravel-sitemap's hreflang support
        // needs a bit more wiring than fits this phase.
        $prefix = '/'.app()->getLocale();

        $sitemap = Sitemap::create()
            ->add(Url::create($prefix.'/')->setPriority(1.0))
            ->add(Url::create($prefix.'/jobs')->setPriority(0.9))
            ->add(Url::create($prefix.'/companies')->setPriority(0.7));

        JobListing::published()
            ->select('slug', 'updated_at')
            ->chunk(500, function ($jobs) use ($sitemap, $prefix) {
                foreach ($jobs as $job) {
                    $sitemap->add(
                        Url::create("{$prefix}/jobs/{$job->slug}")
                            ->setLastModificationDate($job->updated_at)
                            ->setPriority(0.8)
                    );
                }
            });

        Company::where('approval_status', 'approved')->where('status', true)
            ->select('slug', 'updated_at')
            ->chunk(500, function ($companies) use ($sitemap, $prefix) {
                foreach ($companies as $company) {
                    $sitemap->add(
                        Url::create("{$prefix}/companies/{$company->slug}")
                            ->setLastModificationDate($company->updated_at)
                            ->setPriority(0.6)
                    );
                }
            });

        return response($sitemap->render(), 200)->header('Content-Type', 'text/xml');
    }
}
