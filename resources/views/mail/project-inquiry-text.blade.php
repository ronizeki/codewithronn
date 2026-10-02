New project inquiry
@foreach (['name', 'email', 'phone', 'company', 'project_type', 'budget'] as $field)
{{ ucfirst(str_replace('_', ' ', $field)) }}: {{ $inquiry[$field] ?? 'Not provided' }}
@endforeach

Project details:
{{ $inquiry['message'] }}
