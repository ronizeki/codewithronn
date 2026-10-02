@props(['title' => null, 'description' => null, 'path' => '', 'image' => 'images/og.webp', 'noindex' => false])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#183153">
    <x-seo :title="$title ?? 'codewithronn | Fullstack & Laravel Developer Indonesia'" :description="$description ?? 'Fullstack Developer dengan 6+ tahun pengalaman membangun website, custom web application, internal business system, dan Laravel application untuk membantu bisnis bekerja lebih efektif.'" :path="$path" :image="$image" :noindex="$noindex" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <x-analytics />
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <x-navbar />
    <main id="main">{{ $slot }}</main>
    <x-footer />
    <a class="floating-chat" href="{{ \App\Support\Portfolio::whatsapp('Hi Roni, saya menemukan Anda melalui website portfolio dan ingin berdiskusi mengenai project.') }}" aria-label="Chat with Roni on WhatsApp" data-track="whatsapp_clicked"><x-icon name="chat" size="25" /></a>
</body>
</html>
