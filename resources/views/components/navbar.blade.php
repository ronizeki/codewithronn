<header class="site-header">
    <div class="shell nav-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="codewithronn home"><span class="brand-mark">CWR</span><span>{{ config('portfolio.brand') }}<span class="brand-dot">.</span></span></a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="Open navigation"><x-icon name="menu" /></button>
        <nav id="primary-nav" class="primary-nav" aria-label="Main navigation">
            @foreach(['home' => 'Home', 'about' => 'About Me', 'skills' => 'Skill', 'projects' => 'Project', 'contact' => 'Contact Us'] as $id => $label)
                @if($id !== 'projects' || \App\Support\Portfolio::entries('projects')->isNotEmpty())<a href="{{ route('home') }}#{{ $id }}" data-nav="{{ $id }}">{{ $label }}</a>@endif
            @endforeach
            <a class="button button-small" href="{{ \App\Support\Portfolio::whatsapp() }}" data-track="whatsapp_clicked">Let's Talk <x-icon name="external" size="17" /></a>
        </nav>
    </div>
</header>
