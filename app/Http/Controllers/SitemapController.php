<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobListing;
use App\Models\Language;
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
     *
     * Emits one <url> entry per page in EACH active language, with hreflang alternate links
     * pointing at every other language's version of that same page — this is what lets search
     * engines and AI crawlers index/cite the correct locale per searcher instead of only ever
     * seeing the current request's locale.
     */
    public function index(): Response
    {
        $locales = Language::where('status', true)->pluck('code')->toArray();

        if (empty($locales)) {
            $locales = ['en']; // safety net for a fresh install before languages are seeded
        }

        $sitemap = Sitemap::create();

        $this->addStaticPages($sitemap, $locales);
        $this->addJobs($sitemap, $locales);
        $this->addCompanies($sitemap, $locales);

        return response($sitemap->render(), 200)->header('Content-Type', 'text/xml');
    }

    protected function addStaticPages(Sitemap $sitemap, array $locales): void
    {
        foreach (['' => 1.0, 'jobs' => 0.9, 'companies' => 0.7] as $path => $priority) {
            foreach ($locales as $locale) {
                $url = Url::create("/{$locale}/{$path}")->setPriority($priority);

                foreach ($locales as $altLocale) {
                    $url->addAlternate("/{$altLocale}/{$path}", $altLocale);
                }

                $sitemap->add($url);
            }
        }
    }

    protected function addJobs(Sitemap $sitemap, array $locales): void
    {
        JobListing::published()
            ->select('slug', 'updated_at')
            ->chunk(500, function ($jobs) use ($sitemap, $locales) {
                foreach ($jobs as $job) {
                    foreach ($locales as $locale) {
                        $url = Url::create("/{$locale}/jobs/{$job->slug}")
                            ->setLastModificationDate($job->updated_at)
                            ->setPriority(0.8);

                        foreach ($locales as $altLocale) {
                            $url->addAlternate("/{$altLocale}/jobs/{$job->slug}", $altLocale);
                        }

                        $sitemap->add($url);
                    }
                }
            });
    }

    protected function addCompanies(Sitemap $sitemap, array $locales): void
    {
        Company::where('approval_status', 'approved')->where('status', true)
            ->select('slug', 'updated_at')
            ->chunk(500, function ($companies) use ($sitemap, $locales) {
                foreach ($companies as $company) {
                    foreach ($locales as $locale) {
                        $url = Url::create("/{$locale}/companies/{$company->slug}")
                            ->setLastModificationDate($company->updated_at)
                            ->setPriority(0.6);

                        foreach ($locales as $altLocale) {
                            $url->addAlternate("/{$altLocale}/companies/{$company->slug}", $altLocale);
                        }

                        $sitemap->add($url);
                    }
                }
            });
    }
}
