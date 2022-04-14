
@if ($showLabel)
<label for="year-group">Year group</label><br/>
@endif
{{ Form::select($id, $yearGroups, $default, array('id' => $id, 'class' => 'table-dropdown')) }}
