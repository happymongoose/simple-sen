@extends('layouts.app')

@section('page-title')
{{ $student->getFullName() }}
@endsection

@section('content')

<style>


</style>


<x-note-modal instance-identifier="{{ $student->id }}" parent-class="student" update-route="{{ route('notes.update') }}" store-route="{{ route('notes.store') }}" return-route="{{ Request::fullUrl() }}" delete-route="{{ route('notes.delete') }}" own-route="{{ route('notes.own') }}"/>


  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">{{ $student->getFullName() }}</h1>
    </div>

  </div>
  <!-- end .row -->

  <div class="row">

    <!-- key information -->
    <div class="col-sm-12 col-xl-3">
      @include("students.partials.key_information")
    </div>
    <!-- end key info .col -->

    <!-- notes -->
    <div class="col-sm-12 col-xl-9">
      @include("students.partials.notes")
    </div>
    <!-- end notes .col -->

  </div>
  <!-- end .row -->


@endsection

@section('scripts')

<script src="{{ asset("js/tagger.js") }}"></script>

<script>

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

$('#form-save').click(function () {
  //Submit if the form is valid
  if ($('#edit-form').valid()) {
    $('#edit-form').submit();
  }
})

//--------------------------
//
// Note search
//
//--------------------------

$('#note-search-button').click(function(e) {
  e.preventDefault();
  $('#search-form').submit();
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

<script src="{{ asset("js/notes.js") }}"></script>

<script>
notesInit(all_tags);
</script>

@endsection
