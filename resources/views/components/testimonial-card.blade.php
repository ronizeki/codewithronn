@props(['testimonial'])
<figure class="testimonial-card"><span class="quote-mark" aria-hidden="true">“</span><blockquote>{{ $testimonial['quote'] }}</blockquote><figcaption><span class="avatar">{{ mb_substr($testimonial['name'], 0, 1) }}</span><div><strong>{{ $testimonial['name'] }}</strong><span>{{ $testimonial['role'] }}, {{ $testimonial['client'] }}</span></div></figcaption></figure>
