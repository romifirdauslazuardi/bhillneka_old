@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset(\SettingHelper::settings('dashboard', 'logo'))}}" class="logo" alt="{{asset(\SettingHelper::settings('dashboard', 'logo'))}}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
