
@if ($showLabel)
<label for="year-group">Year group</label><br/>
@endif
{{ Form::select('year-group', $yearGroups, $default, array('id' => 'year-group', 'class' => 'table-dropdown')) }}
