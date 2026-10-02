@props(['title' => 'codewithronn | Fullstack & Laravel Developer Indonesia', 'description' => 'Fullstack Developer dengan 6+ tahun pengalaman membangun website, custom web application, internal business system, dan Laravel application untuk membantu bisnis bekerja lebih efektif.', 'path' => '', 'image' => 'images/og.webp', 'noindex' => false])
@php($canonical = \App\Support\Portfolio::canonical($path))
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ !$noindex && \App\Support\Portfolio::indexable() ? 'index, follow' : 'noindex, nofollow' }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('portfolio.brand') }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ \App\Support\Portfolio::canonical($image) }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ \App\Support\Portfolio::canonical($image) }}">
@if(config('analytics.search_console'))<meta name="google-site-verification" content="{{ config('analytics.search_console') }}">@endif
