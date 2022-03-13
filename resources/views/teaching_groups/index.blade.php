@extends('layouts.app')

@section('page-title')
Classes
@endsection

@section('content')


@include('teaching_groups.partials.modal')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Classes</h1>
        <a href="#" id="trigger-add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> New class</a>
    </div>


    <div class="col-sm-12">

      <!-- Show list of all teaching groups -->
      <div class="card shadow mb-4">

        <!-- Title -->
        <!--
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Teaching groups</h6>
        </div>
-->

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Number of students</th>
                  @if ($user->isAdmin())
                  <th class="text-center">Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>

                @foreach ($teaching_groups as $group)

                <?php
                  //Unallocated row always sits at bottom of table
                  if ($group->name=="Unallocated") continue;
                ?>

                <tr>

                  <td class="table-cell-clickable" href="{{ route("teaching_groups.view", [$group->id]) }}">{{ $group->name }}</td>
                  <td class="table-cell-clickable" href="{{ route("teaching_groups.view", [$group->id]) }}">{{ count($group->students )}}</td>

                  @if ($user->isAdmin())
                  <td class="text-center">

                    <a href="#"
                      class="btn btn-icon text-gray-700 trigger-edit" data-url="{{ route("teaching_groups.get", [$group->id]) }}"
                      title="Edit teaching group" data-toggle="tooltip" data-placement="top"
                    >
                      <i class="fas fa-edit"></i>
                    </a>

                    @if ($group->name!="Unallocated")
                    <a href="{{ route('teaching_groups.delete', $group->id) }}"
                      class="btn btn-icon text-gray-700 sa-trigger"
                      title="Delete" data-toggle="tooltip" data-placement="top"
                      sa-title="Are you sure you want to delete {{ $group->name }}?",
                      sa-text="You won't be able to undo this change, and any students in this class will be moved into the 'unallocated' group.",
                      sa-icon="warning",
                      sa-show-cancel-button="true",
                      sa-confirm-text="Yes, delete this class",
                      sa-cancel-text="Cancel"
                    >
                      <i class="fas fa-trash-alt"></i>
                    </a>
                    @endif

                  </td>
                  @endif

                </tr>
                @endforeach

                @foreach ($teaching_groups as $group)
                  @if ($group->name=="Unallocated")
                  <tr class="table-info">
                    <td class="table-cell-clickable" href="{{ route('teaching_groups.edit', $group->id) }}">{{ $group->name }}</td>
                    <td class="table-cell-clickable" href="{{ route('teaching_groups.edit', $group->id) }}">{{ count($group->students )}}</td>
                    <td></td>
                  @endif
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

  <x-table-footer :rows="$teaching_groups" :max-rows="$max_results" />

@endsection

@section('scripts')

<script>

//Update the table length and reload
$('#table_length').change(function () {
    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set("max_results", $('#table_length').val());
    window.location.search = searchParams.toString();
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
  $('#edit-form').attr('action', '{{ route("teaching_groups.store") }}');
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

//Trigger editing dialog (edit)
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
    $('#edit-form').attr('action', '{{ route("teaching_groups.update") }}');
    $('#modal-title').text("Edit class");
    $('#name').val(data['name']);
    $('#id').val(data['id']);

    //Reset errors on dialog validation
    validator.resetForm();
    $('#edit-modal .invalid-feedback').remove()
    $('#edit-modal input').removeClass('is-valid');
    $('#edit-modal input').removeClass('is-invalid');

    //Show modal
    $('#edit-modal').modal('show');
  })
  .fail(function() {
    console.log("Unable to populate modal with teaching group (#edit-modal)");
  })

});

$('#form-save').click(function () {
  //Submit if the form is valid
  if ($('#edit-form').valid()) {
    $('#edit-form').submit();
  }
});

</script>


@endsection
