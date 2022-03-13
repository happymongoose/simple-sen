  @extends('layouts.app')

@section('page-title')
Classes
@endsection

@section('content')

@include('students.partials.key_information_modal')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Students</h1>
        <a href="#" id="trigger-add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> New student</a>
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
                  <th>Name</th>
                  <th>Year group</th>
                  <th>Class</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($students as $student)
                <tr>

                  <td class="table-cell-clickable" href="{{ route('students.edit', $student->id) }}">{{ $student->getFullName() }}</td>
                  <td class="table-cell-clickable" href="{{ route('students.edit', $student->id) }}">{{ $student->year->name }}</td>
                  <td class="table-cell-clickable" href="{{ route('students.edit', $student->id) }}">{{ $student->teaching_group->name }}</td>

                  <td class="text-center">

                    <a href="{{ route('students.delete', $student->id ) }}"
                      class="btn btn-icon text-gray-700 sa-trigger"
                      title="Delete" data-toggle="tooltip" data-placement="top"
                      sa-title="Are you sure you want to delete {{ $student->getFullName() }}?",
                      sa-text="You won't be able to undo this change, and any data for this student will be deleted forever.",
                      sa-icon="warning",
                      sa-show-cancel-button="true",
                      sa-confirm-text="Yes, delete this student",
                      sa-cancel-text="Cancel"
                    >
                      <i class="fas fa-trash-alt"></i>
                    </a>

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

  <x-table-footer :rows="$students" :max-rows="$max_results" />

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
          first_name: {
              required: true,
              minlength: 1,
              maxlength: 255,
          },
          last_name: {
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
  $('#edit-form').attr('action', '{{ route("students.store") }}');
  $('#modal-title').text("Add a new class");
  $('#name').val('');

  //Reset errors on dialog validation
  validator.resetForm();
  $('#edit-modal .invalid-feedback').remove()
  $('#edit-modal input').removeClass('is-valid');
  $('#edit-modal input').removeClass('is-invalid');

  //Show modal
  $('#edit-modal').modal('show');

});


$('#form-save').click(function () {
  //Submit if the form is valid
  if ($('#edit-form').valid()) {
    $('#edit-form').submit();
  }
});

//--------------------------
//
// Tag editor
//
//--------------------------

var all_tags = [{!! $all_tags_as_string !!}];

var student_tags = tagger($('#student-tags'), {
  allow_duplicates: false,
  allow_spaces: false,
  add_on_blur: true,
  wrap: true,
  link: function() { return false; },
  completion: {
    list: all_tags
  }
});

</script>


@endsection
