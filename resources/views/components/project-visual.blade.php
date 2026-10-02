@props(['project', 'eager' => false])
@if(!empty($project['image']))
    <x-responsive-image :path="$project['image']" :alt="$project['title'].' interface'" width="1200" height="800" sizes="(max-width: 767px) 100vw, 50vw" :eager="$eager" />
@else
    <div class="project-placeholder" role="img" aria-label="Project image to be added">
        <span class="project-placeholder-label">{{ $project['category'] }}</span>
        <div class="project-placeholder-window" aria-hidden="true"><div><i></i><i></i><i></i></div><span></span><span></span><section><i></i><i></i><i></i></section></div>
        <span class="project-placeholder-caption">Project image to be added</span>
    </div>
@endif
