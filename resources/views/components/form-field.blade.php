@props(['name', 'label', 'type' => 'text', 'required' => false, 'options' => null])
<div {{ $attributes->only('class')->class(['form-field']) }}><label for="{{ $name }}">{{ $label }} @if($required)<span aria-hidden="true">*</span>@else<span class="optional">(optional)</span>@endif</label>
@if($options)
<select id="{{ $name }}" name="{{ $name }}" @required($required) aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}" @if($errors->has($name)) aria-describedby="{{ $name }}-error" @endif><option value="">{{ $required ? 'Select project type' : 'Select your budget' }}</option>@foreach($options as $option)<option value="{{ $option }}" @selected(old($name) === $option)>{{ $option }}</option>@endforeach</select>
@elseif($type === 'textarea')
<textarea id="{{ $name }}" name="{{ $name }}" rows="5" minlength="20" maxlength="5000" placeholder="Tell me about your business, what you need, and any timeline you have in mind…" @required($required) aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}" @if($errors->has($name)) aria-describedby="{{ $name }}-error" @endif>{{ old($name) }}</textarea>
@else
<input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name) }}" {{ $attributes->except('class') }} @required($required) aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}" @if($errors->has($name)) aria-describedby="{{ $name }}-error" @endif>
@endif
@error($name)<p id="{{ $name }}-error" class="field-error">{{ $message }}</p>@enderror
</div>
