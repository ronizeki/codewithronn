@php
    $ga = preg_match('/^G-[A-Z0-9]+$/', config('analytics.ga4') ?? '') ? config('analytics.ga4') : null;
    $gtm = preg_match('/^GTM-[A-Z0-9]+$/', config('analytics.gtm') ?? '') ? config('analytics.gtm') : null;
@endphp
@if($gtm)
<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push({'gtm.start':Date.now(),event:'gtm.js'});</script>
<script async src="https://www.googletagmanager.com/gtm.js?id={{ $gtm }}"></script>
@elseif($ga)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga }}"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',{{ Illuminate\Support\Js::from($ga) }});</script>
@endif
