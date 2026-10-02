@if(config('portfolio.profile'))
    <div class="portrait-frame identity-panel">
        <x-responsive-image :path="config('portfolio.profile')" width="800" height="960" :alt="config('portfolio.name')" sizes="(max-width: 600px) 90vw, 40vw" :eager="true" />
        <span class="portrait-label">{{ config('portfolio.name') }}<span>{{ config('portfolio.role') }}</span></span>
    </div>
@else
    <div class="profile-card">
        <div class="profile-card-top"><span class="profile-monogram">CWR</span><span>{{ config('portfolio.brand') }}</span></div>
        <div class="profile-card-main">
            <span class="profile-overline">YOUR DEVELOPMENT PARTNER</span>
            <p class="profile-name">{{ config('portfolio.name') }}</p>
            <p class="profile-role">{{ config('portfolio.role') }}</p>
            <span class="profile-rule" aria-hidden="true"></span>
            <p class="profile-statement">Thoughtful code.<br>Real business impact.</p>
        </div>
        <div class="profile-card-bottom"><div><strong>{{ config('portfolio.experience') }}</strong><span>Years of experience</span></div><span class="profile-location"><x-icon name="pin" size="16" /> Indonesia</span></div>
    </div>
@endif
