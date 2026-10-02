@props(['service', 'number'])
<article class="service-card" data-aos="fade-up"><div class="service-top"><span class="icon-tile"><x-icon :name="$service['icon']" /></span><span class="card-number">0{{ $number }}</span></div><h3>{{ $service['title'] }}</h3><p>{{ $service['text'] }}</p><p class="service-detail">{{ $service['detail'] }}</p></article>
