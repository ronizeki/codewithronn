@props(['problem'])
<article class="problem-card"><span class="line-icon"><x-icon :name="$problem['icon']" /></span><h3>{{ $problem['title'] }}</h3><p>{{ $problem['text'] }}</p></article>
