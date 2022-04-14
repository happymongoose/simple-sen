@extends('layouts.app')

@section('page-title')
Year groups
@endsection

@section('content')

@include('year_groups.partials.modal')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Year groups</h1>
    </div>


    <div class="col-sm-12">

      <!-- Show list of all teaching groups -->
      <div class="card shadow mb-4">

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Year group</th>
                  <th class="text-center">Number of students</th>
                  <th class="text-center">In use?</th>
                  @if ($user->isAdmin())
                  <th class="text-center">Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>

                @foreach ($year_groups as $group)

                <?php
                  //Unallocated row always sits at bottom of table
                  if ($group->name=="Unallocated") continue;
                ?>

                <tr>

                  <td class="table-cell-clickable" href="{{ route("year_groups.view", [$group->id]) }}">{{ $group->name }}</td>
                  <td class="table-cell-clickable text-center" href="{{ route("year_groups.view", [$group->id]) }}">{{ count($group->students )}}</td>
                  <td class="table-cell-clickable text-center" href="{{ route("year_groups.view", [$group->id]) }}">@if ($group->show) Yes @else No @endif</td>

                  @if ($user->isAdmin())
                  <td class="text-center">

                    <a href="#"
                      class="btn btn-icon text-gray-700 trigger-edit" data-url="{{ route("year_groups.get", [$group->id]) }}"
                      title="Edit teaching group" data-toggle="tooltip" data-placement="top"
                    >
                      <i class="fas fa-edit"></i>
                    </a>

                  </td>
                  @endif

                </tr>
                @endforeach

                @foreach ($year_groups as $group)
                  @if ($group->name=="Unallocated")
                  <tr class="table-info">
                    <td class="table-cell-clickable" href="{{ route('year_groups.edit', $group->id) }}">{{ $group->name }}</td>
                    <td class="table-cell-clickable" href="{{ route('year_groups.edit', $group->id) }}">{{ count($group->students )}}</td>
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

  <x-table-footer :rows="$year_groups" :max-rows="$max_results" />

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
          "year-group-name": {
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
    $('#edit-form').attr('action', '{{ route("year_groups.update") }}');
    $('#modal-title').text("Edit year group");
    $('#year-group-name').val(data['name']);
    $('#year-group-show').prop('checked', data['show']==1);
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
    console.log("Unable to populate modal with year group (#edit-modal)");
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
