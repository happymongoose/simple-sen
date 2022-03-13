
<!-- Modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">
          Add user</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! Form::open(['route' => ['users.store'], 'method' => 'PUT', 'id' => 'edit-form', 'name' => 'edit-form']) !!}

          <!-- First name -->
          <label for="first_name">Email</label>

          <div class="form-group">
            <input
            type="text"
            class="form-control form-control-user"
            id="email"
            name="email"
            aria-describedby="emailHelp"
            value=""
            required
            placeholder="Email address or user name"
            autofocus>
          </div>

          <div class="form-group">
            <x-roles-dropdown :roles="$roles" default="1" showLabel="true" />
          </div>

          <!-- Surname -->
          <label for="last_name">Name</label>

          <div class="form-group">
            <input
            type="text"
            class="form-control form-control-user"
            id="name"
            name="name"
            aria-describedby="nameHelp"
            value=""
            required
            placeholder=""
            >
          </div>

          <!-- Password -->
          <label for="password">Password</label>

          <div class="form-group">
            <input
            type="text"
            class="form-control form-control-user"
            id="password"
            name="password"
            aria-describedby="passwordHelp"
            value=""
            placeholder=""
            >
          </div>

          <!-- Password confirmation -->
          <label for="password_confirmation">Confirm password</label>

          <div class="form-group">
            <input
            type="text"
            class="form-control form-control-user"
            id="password-confirm"
            name="password-confirm"
            aria-describedby="passwordHelp"
            value=""
            placeholder="Retype password"
            >
            <p id="password-error" class="error mt-2" style="width:100%">Password and confirmation password must match</p>
          </div>

          <p id="change-password-info">To change the user's password, type it into the 'password' and 'confirm password' text boxes above.</p>

        <input type="hidden" value="" id="form-user-id" name="form-user-id">
        <input type="hidden" name="form-action" value="add-update" >
        <input type="hidden" name="form-action" value="add-update" >

        {!! Form::close() !!}

      </div>
      <div class="modal-footer">

        <!-- delete button -->
        <div id="delete-section">
          <a href="#" class="btn btn-danger btn-icon-split mr-3" data-name="" id="user-delete">
            <span class="icon text-white-50">
              <i class="fas fa-trash"></i>
            </span>
            <span class="text">Delete</span>
          </a>
        </div>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="form-save">Save</button>
      </div>
    </div>
  </div>
</div>
