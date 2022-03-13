
@if ($showLabel)
<label for="role">Role</label><br/>
@endif
{{ Form::select('role', $roles, $default, array('id' => 'role', 'class' => 'table-dropdown')) }}
