@extends('layouts.app')

@section('page-title')
Adult users
@endsection

@section('content')

@include('users.partials.modal')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users</h1>
        <a href="#" id="trigger-add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> New adult</a>
    </div>


    <div class="col-sm-12">

      <!-- Show list of all students -->
      <div class="card shadow mb-4">

        <!-- Title -->
        <!--
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Teaching groups</h6>
        </div>
-->

        <div class="card-body">

          <div class="row">
            <div class="col-sm-8">
              <form
                  class="d-none d-sm-inline-block form-inline mr-auto navbar-search mb-3"
                  style="width:95%" id="search-form">
                  <div class="input-group">
                      <input
                        type="text"
                        class="form-control bg-light border-0 small"
                        placeholder="Search by name or tag..."
                        aria-label="Search" aria-describedby="basic-addon2"
                        value="{{ Request::input('search') }}"
                        name="search"
                        id="search-text"
                      >
                      <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="search-button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                      </div>
                  </div>
              </form>
            </div>
          </div>
          <!-- end .row -->

          <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Email / username</th>
                  <th>Name</th>
                  <th>Role</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($users as $user)
                <tr>

                  <td class="table-cell-clickable trigger-edit" href="#" data-url="{{ route('users.get', $user->id) }}">{{ $user->email }}</td>
                  <td class="table-cell-clickable trigger-edit" href="#" data-url="{{ route('users.get', $user->id) }}">{{ $user->name }}</td>
                  <td class="table-cell-clickable trigger-edit" href="#" data-url="{{ route('users.get', $user->id) }}">{{ $user->role->name }}</td>

                  <td class="text-center">

                  </td>

                </tr>
                @endforeach

              </tbody>
            </table>

          </div>
        </div>
      </div>

    </div>
    <!-- end col -->


  </div>
  <!-- end row -->

  <x-table-footer :rows="$users" :max-rows="$max_results" />


@endsection

@section('scripts')

<script src="{{ asset("js/tagger.js") }}"></script>

<script>

//Update the table length and reload
$('#table_length').change(function () {
    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set("max_results", $('#table_length').val());
    window.location.search = searchParams.toString();
})

$('#search-button').click(function(e) {
    e.preventDefault();
    $('#search-form').submit();
});

//--------------------------
//
// Validation
//
//--------------------------

//Input validator
var validator;

$(document).ready(function () {
  validator = $('#edit-form').validate({
      rules: {
          name: {
              required: true,
              minlength: 1,
              maxlength: 255,
          },
          email: {
              required: true,
              minlength: 1,
              maxlength: 255,
          },
      },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
  });
});

//--------------------------
//
// Modal
//
//--------------------------

//Trigger editing dialog (add)
$('#trigger-add').click(function (e) {

  //Prevent default action
  e.preventDefault();

  //Insert data into add dialog
  $('#edit-form').attr('action', '{{ route("users.store") }}');
  $('#modal-title').text("Add a new adult");
  $('#name').val('');
  $('#email').val('');
  $('#role').val(1);
  $('#password').val('');
  $('#password-confirm').val('');
  $('#change-password-info').hide();
  $("#password").prop('required',true);
  $("#password-confirm").prop('required',true);
  $('#delete-section').hide();

  //Reset errors on dialog validation
  validator.resetForm();
  $('#edit-modal .invalid-feedback').remove()
  $('#edit-modal input').removeClass('is-valid');
  $('#edit-modal input').removeClass('is-invalid');
  $('#password-error').hide();

  //Show modal
  $('#edit-modal').modal('show');

});

//Trigger editing dialog (edit))
$('.trigger-edit').click(function (e) {

  //Prevent default action
  e.preventDefault();

  //Get fetch URL
  var url = $(this).data('url');

  //Load view
  $.ajax({
    url: url,
    type: 'GET',
    datatype: 'html'
  })
  .done(function(data) {

    //Insert data into edit dialog
    $('#edit-form').attr('action', '{{ route("users.update") }}');
    $('#modal-title').text("Edit adult");
    $('#name').val(data['name']);
    $('#email').val(data['email']);
    $('#role').val(data['role_id']);
    $('#password').val('');
    $('#password-confirm').val('');
    $('#change-password-info').show();
    $("#password").prop('required',false);
    $("#password-confirm").prop('required',false);
    $('#delete-section').show();
    $('#user-delete').data('name', data['name']);

    $('#form-user-id').val(data['id']);

    //Reset errors on dialog validation
    validator.resetForm();
    $('#edit-modal .invalid-feedback').remove()
    $('#edit-modal input').removeClass('is-valid');
    $('#edit-modal input').removeClass('is-invalid');
    $('#password-error').hide();

    //Show modal
    $('#edit-modal').modal('show');
  })
  .fail(function() {
    console.log("Unable to populate modal with teaching group (#edit-modal)");
  })

});

$('#form-save').click(function () {
  //Submit if the form is valid
  if (!$('#edit-form').valid())
    return;

  //Check passwords match (if something is entered)
  if ($('#password').val().trim()!="") {
    if ( $('#password').val() != $('#password-confirm').val() ) {
      $('#password-error').show();
      return;
    }
  }

  //Remove password error message
  $('#password-error').hide();

  //Submit form
  $('#edit-form').submit();
});

//--------------------------
//
// Delete note
//
//--------------------------

$('#user-delete').click(function (e) {

  //Prevent default action
  e.preventDefault();

  Swal.fire({
    title: "Are you sure you want to delete user '" + $('#user-delete').data('name') + "'?",
    text: "You won't be able to undo this action.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete them'
    }
  ).then((result)=>{
    if (result.isConfirmed) {
      //Convert from from an add-update form to a delete form
      $('#edit-form').attr('action', "{{ route('users.delete')}}?id=" + $('#form-user-id').val());
      $('#form-action').val("delete");
      $('#edit-form').submit();
    }
  });

});

</script>

@endsection
