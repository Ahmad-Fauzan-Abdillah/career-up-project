<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\Training;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        return response(
            "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /beranda\nDisallow: /profil\nDisallow: /pengaturan\nDisallow: /riwayat\nDisallow: /notifikasi\nSitemap: ".route('sitemap')."\n",
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8'],
        );
    }

    public function sitemap(): Response
    {
        $jobs = JobListing::published()
            ->where(static fn ($query) => $query->whereNull('deadline')->orWhereDate('deadline', '>=', today()))
            ->get(['slug', 'type', 'updated_at']);
        $trainings = Training::published()->get(['slug', 'updated_at']);

        return response()
            ->view('seo.sitemap', compact('jobs', 'trainings'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
