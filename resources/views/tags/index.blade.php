@extends('layouts.app')

@section('page-title')
Tags
@endsection

@section('stylesheets')

@endsection

@section('styles')

<style>

.tag-colour-box {
    width: 32px;
    height: 32px;
    border: 1px solid black;
}

</style>

@endsection


@section('content')


@include('tags.partials.modal')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tags</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="trigger-add"><i
                class="fas fa-plus fa-sm text-white-50"></i> New tag</a>
    </div>


    <div class="col-sm-12">

      <!-- Show list of all tags -->
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
                        placeholder="Search by tag text or description..."
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
                  <th>Tag</th>
                  <th>Description</th>
                  <th class="text-center">Colour</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($tags as $tag)

                <?php
                  //Get the number of students with this tag
                  $students_with_tag = count($tag->students);

                  //If this tag is attached to at least one student, explain the implications of deleting
                  if ($students_with_tag>0) {
                    $delete_msg = "This tag will be removed from " . $students_with_tag . " student";
                    if ($students_with_tag>1) $delete_msg .= "s";
                    $delete_msg .= " and you won't be able to undo this change.";
                  } else {
                    //Otherwise, just let the user know this is irreversible
                    $delete_msg = "You won't be able to undo this change.";
                  }
                ?>
                <tr>

                  <td class="table-cell-clickable trigger-edit" data-url="{{ route("tags.get", [$tag->id]) }}">{{ $tag->tag }}</td>
                  <td class="table-cell-clickable trigger-edit" data-url="{{ route("tags.get", [$tag->id]) }}">{{ $tag->description }}</td>
                  <td class="table-cell-clickable trigger-edit" data-url="{{ route("tags.get", [$tag->id]) }}"><div class="tag-colour-box mx-auto" style="background-color: #{{ $tag->colour }}"></div></td>

                  <td class="text-center">

                    <a href="{{ route('students.index', ['search'=>'tag:' . $tag->tag]) }}"
                      class="btn btn-icon text-gray-700"
                      title="Show all students with tag '{{ $tag->tag }}'" data-toggle="tooltip" data-placement="top"
                    >
                      <i class="fas fa-list"></i>
                    </a>

                  @if ($user->isAdmin())
                    <a href="{{ route('tags.delete', [$tag->id]) }}"
                      class="btn btn-icon text-gray-700 sa-trigger"
                      title="Delete" data-toggle="tooltip" data-placement="top"
                      sa-title="Are you sure you want to delete the tag '{{ $tag->tag }}'?",
                      sa-text="{{ $delete_msg }}",
                      sa-icon="warning",
                      sa-show-cancel-button="true",
                      sa-confirm-text="Yes, delete this tag",
                      sa-cancel-text="Cancel"
                    >
                      <i class="fas fa-trash-alt"></i>
                    </a>
                  @endif

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

  <x-table-footer :rows="$tags" :max-rows="$max_results" />


@endsection


@section('scripts')

<script>

//--------------------------
//
// Variables
//
//--------------------------

//Colorpicker instance
var colour_picker;

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
          tag: {
              required: true,
              minlength: 1,
              maxlength: 255,
              remote: {
                url: "tags/is-unique",
                type: "post",
                headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                data: {
                  text: function() {
                    return $('#tag').val();
                  },
                  initial: function() {
                    return $('#initial-tag').val();
                  }
                },
              }
          },
          description: {
            maxlength: 255,
          }
      },
      messages: {
        tag: {
          remote: "Tag already in use."
        }
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


//Update the table length and reload
$('#table_length').change(function () {
    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set("max_results", $('#table_length').val());
    window.location.search = searchParams.toString();
})


//Trigger editing dialog
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
    $('#edit-form').attr('action', '{{ route("tags.update") }}');
    $('#modal-title').text("Edit tag");
    $('#tag').val(data['tag']);
    $('#initial-tag').val(data['tag']);
    $('#description').val(data['description']);
    $('#id').val(data['id']);
    $('#colour').val("data['colour']");
    $('#edit-tag-colour').css("background-color", "#"+data['colour']);
    @if (Auth::user()->isAdmin())
      $('#modal-delete-group').show();
    @endif
    $('#tag-data').data('student-count', data['student_count']);

    //Update colour picker default colour
    colour_picker.setValue(data['colour']);

    //Reset errors on dialog validation
    validator.resetForm();
    $('#edit-modal .invalid-feedback').remove()
    $('#edit-modal input').removeClass('is-valid');
    $('#edit-modal input').removeClass('is-invalid');

    //Show modal
    $('#edit-modal').modal('show');
  })
  .fail(function() {
    console.log("Unable to populate modal with tag (#edit-modal)");
  })

});


//Trigger editing dialog
$('#trigger-add').click(function (e) {

  //Prevent default action
  e.preventDefault();

  //Get fetch URL
  var url = $(this).data('url');

  //Insert data into add dialog
  $('#edit-form').attr('action', '{{ route("tags.store") }}');
  $('#modal-title').text("Add a new tag");
  $('#tag').val('');
  $('#initial-tag').val('');
  $('#description').val('');
  $('#id').val('');
  $('#colour').val("#{{ $next_tag_colour }}");
  $('#edit-tag-colour').css("background-color", "#{{ $next_tag_colour }}");
  $('#modal-delete-group').hide();

  //Update colour picker default colour
  colour_picker.setValue("#{{ $next_tag_colour }}");

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
    $('#colour').val(colour_picker.getValue().substring(1));
    $('#edit-form').submit();
  }
});

//--------------------------
//
// Colour picker
//
//--------------------------

$(function () {
  // Basic instantiation:
  $('#colour-group').colorpicker({
    useAlpha:false,
    format: "hex",
    useHashPrefix: false,
    extensions: [
      {
        name: 'swatches',
        options: {
          colors: { {!! $tag_colours_string !!} },
          namesAsValues: true
        }
      }
    ]
  }).on('colorpickerCreate', function(e) {
    colour_picker = e.colorpicker;
  });

});

$('#search-button').click(function(e) {
    e.preventDefault();
    $('#search-form').submit();
});

</script>


@endsection
