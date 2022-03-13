<!-- Personal information -->

{!! Form::open(['route' => ['students.update', $student->id], 'method' => 'PUT', 'id' => 'edit-form', 'name' => 'edit-form']) !!}

@csrf

<div class="card shadow mb-4">

  <!-- Title -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Key information</h6>
  </div>

  <div class="card-body">

    <!-- First name -->
    <label for="first_name">First name</label>

    <div class="form-group">
      <input
      type="text"
      class="form-control form-control-user"
      id="first_name"
      name="first_name"
      aria-describedby="firstNameHelp"
      value="{{ $student->first_name }}"
      required
      placeholder="Student's first name"
      autofocus>
    </div>

    <!-- Surname -->
    <label for="last_name">Surname</label>

    <div class="form-group">
      <input
      type="text"
      class="form-control form-control-user"
      id="last_name"
      name="last_name"
      aria-describedby="lastNameHelp"
      value="{{ $student->last_name }}"
      required
      placeholder="Student's surname"
      >
    </div>

    <!-- Tags -->
    <label for="tags">Tags</label>
    <x-input-tag name="student-tags" id="student-tags" tags="{!! $student->getTagsAsString() !!}" />

    <!-- Year group -->
    <div class="form-group mt-3">
      <x-year-group-dropdown :year-groups="$year_groups" show-label="true" default="{!! $student->year_group !!}"/>
    </div>

    <!-- Class -->
    <div class="form-group mt-3">
      <x-teaching-group-dropdown :teaching-groups="$teaching_groups" show-label="true" default="{!! $student->teaching_group->id !!}"/>
    </div>

  </div>
  <!-- end .card-body -->

  <div class="card-footer text-right">

    <a href="#" class="btn btn-success btn-icon-split" id="form-save">
      <span class="icon text-white-50">
        <i class="fas fa-save"></i>
      </span>
      <span class="text">Save changes</span>
    </a>

  </div>
  <!-- end .card-footer -->

</div>
<!-- end .card -->

</form>
<!-- end .form -->
