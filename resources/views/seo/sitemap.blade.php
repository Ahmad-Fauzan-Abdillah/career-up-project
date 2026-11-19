<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('landing') }}</loc>
    </url>
    @foreach($jobs as $job)
        <url>
            <loc>{{ route($job->type === 'magang' ? 'magang.show' : 'lowongan.show', $job->slug) }}</loc>
            <lastmod>{{ $job->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach
    @foreach($trainings as $training)
        <url>
            <loc>{{ route('pelatihan.show', $training->slug) }}</loc>
            <lastmod>{{ $training->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>
