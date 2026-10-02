<h1>New project inquiry</h1>
@foreach (['name', 'email', 'phone', 'company', 'project_type', 'budget'] as $field)
    <p><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong> {{ $inquiry[$field] ?? 'Not provided' }}</p>
@endforeach
<h2>Project details</h2>
<p style="white-space: pre-wrap">{{ $inquiry['message'] }}</p>
