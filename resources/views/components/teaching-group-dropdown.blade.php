
@if ($showLabel)
<label for="teaching-group">Class</label><br/>
@endif
{{ Form::select('teaching-group', $teaching_groups, $default, array('id' => 'teaching-group', 'class' => 'table-dropdown')) }}
