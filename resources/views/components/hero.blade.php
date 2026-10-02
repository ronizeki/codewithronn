<section id="home" class="hero shell">
    <div class="hero-copy">
        @if(config('portfolio.available'))<div class="availability"><span></span> Available for freelance projects</div>@endif
        <p class="eyebrow hero-eyebrow">FULLSTACK DEVELOPER · INDONESIA</p>
        <h1>Thoughtful code.<br>Real business<br><span class="accent">impact.</span><span class="heading-dot">↗</span></h1>
        <p class="hero-description">I'm <strong>Roni Zeki</strong>, a Fullstack Developer with <strong>6+ years of experience.</strong> I build reliable websites and web applications that help your business work better and grow.</p>
        <div class="button-row"><a class="button" href="{{ \App\Support\Portfolio::whatsapp() }}" data-track="whatsapp_clicked">Discuss Your Project <x-icon name="external" size="19" /></a>@if(\App\Support\Portfolio::entries('projects')->isNotEmpty())<a class="text-link" href="#projects">View My Work <x-icon name="arrow" size="18" /></a>@else<a class="text-link" href="#services">Explore My Services <x-icon name="arrow" size="18" /></a>@endif</div>
        <div class="hero-stats"><div><strong>{{ config('portfolio.experience') }}</strong><span>Years of experience</span></div></div>
    </div>
    <div class="hero-visual">
        <x-profile-visual />
    </div>
</section>
