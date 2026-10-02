@props(['project', 'number'])
<article class="project-card" data-category="{{ $project['category'] }}" data-aos="fade-up">
    <a class="project-image" href="{{ route('projects.show', $project['slug']) }}" aria-label="View {{ $project['title'] }} case study" data-track="project_viewed" data-project="{{ $project['slug'] }}"><x-project-visual :project="$project" /><span class="project-open"><x-icon name="external" /></span></a>
    @if($project['placeholder'] ?? false)<p class="project-edit-note">Project details to be updated</p>@endif
    <div class="project-meta"><span>{{ $project['client'] }}</span><span>0{{ $number }} / {{ $project['category'] }}</span></div>
    <h3><a href="{{ route('projects.show', $project['slug']) }}" data-track="project_viewed" data-project="{{ $project['slug'] }}">{{ $project['title'] }}</a></h3><p>{{ $project['description'] }}</p>
    <div class="tags">@foreach($project['tech'] as $tech)<span>{{ $tech }}</span>@endforeach</div>
    <details class="project-details"><summary>Problem & solution</summary><p><strong>Problem:</strong> {{ $project['problem'] }}</p><p><strong>Solution:</strong> {{ $project['solution'] }}</p></details>
    <a class="text-link case-link" href="{{ route('projects.show', $project['slug']) }}" data-track="project_viewed" data-project="{{ $project['slug'] }}">View Case Study <x-icon name="external" size="17" /></a>
</article>
