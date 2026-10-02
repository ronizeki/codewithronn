<div class="contact-form-card">
@if(session('contact_success'))<div class="form-notice success" role="status" tabindex="-1" data-form-result @if(session('contact_delivered')) data-contact-delivered @endif>{{ session('contact_success') }}</div>@endif
@if($errors->any())<div class="form-notice error" role="alert" tabindex="-1" data-form-result><strong>Please check your message.</strong><ul>@foreach($errors->getMessages() as $field => $messages)<li>@if(in_array($field, ['delivery', 'website'])){{ $messages[0] }}@else<a href="#{{ $field }}">{{ $messages[0] }}</a>@endif</li>@endforeach</ul></div>@endif
<form action="{{ route('contact.store') }}" method="POST" id="contact-form">
    @csrf
    <div class="form-grid">
        <x-form-field name="name" label="Your name" required autocomplete="name" maxlength="100" placeholder="Your full name" />
        <x-form-field name="email" label="Email address" type="email" required autocomplete="email" maxlength="254" placeholder="you@company.com" />
        <x-form-field name="phone" label="Phone / WhatsApp" type="tel" autocomplete="tel" maxlength="30" placeholder="+62" />
        <x-form-field name="company" label="Company" autocomplete="organization" maxlength="150" placeholder="Your company" />
        <x-form-field name="project_type" label="Project type" required :options="config('portfolio.project_types')" />
        <x-form-field name="budget" label="Estimated budget" :options="config('portfolio.budgets')" />
        <x-form-field name="message" label="Tell me about your project" type="textarea" required class="full-width" />
    </div>
    <div class="honeypot" aria-hidden="true"><label for="website">Leave this field empty</label><input type="text" id="website" name="website" tabindex="-1" autocomplete="off"></div>
    <button class="button form-submit" type="submit">Send Message <x-icon name="external" size="19" /></button>
    <p class="form-privacy">Your details will only be used to respond to your inquiry. Fields marked * are required.</p>
</form>
</div>
