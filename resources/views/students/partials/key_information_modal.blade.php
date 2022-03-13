
<!-- Modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">
          Add student</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['route' => ['students.store'], 'method' => 'PUT', 'id' => 'edit-form', 'name' => 'edit-form']) !!}

          <!-- First name -->
          <label for="first_name">First name</label>

          <div class="form-group">
            <input
            type="text"
            class="form-control form-control-user"
            id="first_name"
            name="first_name"
            aria-describedby="firstNameHelp"
            value=""
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
            value=""
            required
            placeholder="Student's surname"
            >
          </div>

          <!-- Tags -->
          <label for="tags">Tags</label>
          <x-input-tag name="student-tags" id="student-tags" tags="" />

          <div class="row">

            <!-- Year group -->
            <div class="col-sm-6">
                <div class="form-group mt-3">
                  <x-year-group-dropdown :year-groups="$year_groups" show-label="true" default=""/>
                </div>
            </div>
            <!-- end .col -->

            <!-- Class -->
            <div class="col-sm-6">
              <div class="form-group mt-3">
                <x-teaching-group-dropdown :teaching-groups="$teaching_groups" show-label="true" default=""/>
              </div>
            </div>
            <!-- end .col -->

        </div>
        <!-- end .row -->

          <input type="hidden" value="" id="id" name="id">
        {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="form-save">Save</button>
      </div>
    </div>
  </div>
</div>
