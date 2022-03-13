@extends('layouts.app')

@section('page-title')
{{ $editing ? "Edit cohort" : "Add cohort" }}
@endsection

@section('stylesheets')

@endsection

@section('styles')

@endsection


@section('content')

@if ($editing)
{!! Form::open(['route' => ['cohorts.update', $cohort], 'method' => 'PUT', 'id' => 'edit-form', 'name' => 'edit-form']) !!}
@else
{!! Form::open(['route' => ['cohorts.store'], 'method' => 'PUT', 'id' => 'edit-form', 'name' => 'edit-form']) !!}
@endif

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $editing ? "Edit cohort" : "Create a new cohort" }}</h1>
    </div>

    <div class="col-sm-12">

      <div class="card shadow mb-4 ml-2 mr-2 mt-4" >
        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Key information</div>

        <div class="card-body">

          <!-- Cohort description -->
          <label for="description">Description<br/></label>

          <div class="form-group">
            <input
            type="text"
            class="form-control form-control-user"
            id="description"
            name="description"
            aria-describedby="descriptionHelp"
            value="{{ $editing ? $cohort->description : '' }}"
            required
            placeholder="KS1 pupils with EHCP"
            autofocus>
          </div>

        </div>
      </div>

    </div>

    <div class="col-sm-8 condition-drop-area">

      <div class="card shadow mb-4 ml-2 mr-2 mt-4" >
        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Rules</h6>Drag the rules for being part of this cohort into the space below. If you need to remove a rule, drag it back to the rule bank.<div id="rules-error" style="width: 100%" class="error invalid-feedback"><br/>Add at least one rule to say which students will be part of this cohort.</div></div>

        <div class="card-body pt-0 pb-3" id="condition-area" style="min-height: 500px; background-color: #99999a">
        </div>
      </div>

      <div class="mb-4 ml-2">
        <a href="#" class="btn btn-success btn-icon-split" id="form-save">
          <span class="icon text-white-50">
            <i class="fas fa-save"></i>
          </span>
          <span class="text">Save cohort</span>
        </a>
      </div>

    </div>

    <div class="col-sm-4 condition-drop-area">

      <div class="card shadow mb-4 ml-2 mr-2 mt-4 pb-3" id="condition-source" style="min-height: 500px; background-color: #4e73df80">

        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Rule bank</div>

        <div class="card shadow mb-0 mt-0 ml-2 mr-2 draggable ui-widget-content condition-block disabled mt-4" id="condition-year-group" style="z-index:999" data-input-control="input-year-groups" data-error-id="condition-year-group-error">
          <div class="card-header"><i class="fas fa-arrows-alt"></i> Pupil is in any of the following year groups:</div>
          <div class="card-body">
            <x-input-tag name="input-year-groups" id="input-year-groups" tags="" />
            <div id="condition-year-group-error" style="width: 100%" class="error invalid-feedback"><br/>Rule must include at least one year group.</div>
          </div>
        </div>

        <div class="card shadow mb-0 mt-0 ml-2 mr-2 draggable ui-widget-content condition-block disabled mt-4" id="condition-class" style="z-index:999" data-input-control="input-classes" data-error-id="condition-class-error">
          <div class="card-header"><i class="fas fa-arrows-alt"></i> Pupil is in any of the following classes:</div>
          <div class="card-body">
            <x-input-tag name="input-classes" id="input-classes" tags=""/>
            <div id="condition-class-error" style="width: 100%" class="error invalid-feedback"><br/>Rule must include at least one class.</div>
          </div>
        </div>

        <div class="card shadow mb-0 mt-0 ml-2 mr-2 draggable ui-widget-content condition-block disabled mt-4" id="condition-all-tags" style="z-index:999" data-input-control="input-all-tags" data-error-id="condition-all-tags-error">
          <div class="card-header"><i class="fas fa-arrows-alt"></i> Pupil has all of the following tags:</div>
          <div class="card-body">
            <x-input-tag name="input-all-tags" id="input-all-tags" tags=""/>
            <div id="condition-all-tags-error" style="width: 100%" class="error invalid-feedback"><br/>Rule must include at least one tag.</div>
          </div>
        </div>

        <div class="card shadow mb-0 mt-0 ml-2 mr-2 draggable ui-widget-content condition-block disabled mt-4" id="condition-any-tag" style="z-index:999" data-input-control="input-any-tag" data-error-id="condition-any-tag-error">
          <div class="card-header"><i class="fas fa-arrows-alt"></i> Pupil has any of the following tags:</div>
          <div class="card-body">
            <x-input-tag name="input-any-tag" id="input-any-tag" tags=""/>
            <div id="condition-any-tag-error" style="width: 100%" class="error invalid-feedback"><br/>Rule must include at least one tag.</div>
          </div>
        </div>

      </div>
      <!-- end container card -->

    </div>
    <!-- end .col -->


  </div>
  <!-- end row -->

</form>

@endsection


@section('scripts')

<script src="{{ asset("js/tagger.js") }}"></script>
<script src="{{ asset("js/cohort-conditions.js") }}"></script>

<script>


//--------------------------
//
// Tag editor
//
//--------------------------

var all_tags = [{!! $all_tags_as_string !!}];
var all_year_tags = [{!! $all_year_groups_as_string !!}];
var all_class_tags = [{!! $all_teaching_groups_as_string !!}];
var editing = {{ $editing ? "true" : "false" }};
@if ($editing)
var condition_data = @json($cohort->conditions);
@endif

//--------------------------
//
// Cohort conditions
//
//--------------------------

$(document).ready(function () {

  //Initialise cohort conditions drap-and-drop
  cohortConditionsInit(all_tags, all_year_tags, all_class_tags);

  //If editing, fill in condition blocks and move them into the correct position
  if (editing)
    cohortConditionsFill(condition_data);

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
        description: {
          required: true,
          minlength: 1,
          maxlength: 255
        }
      },
      messages: {
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

//Checks that rules have been added (and are valid)
function validateRules() {

  //Hide any previous error message about the rules
  $('#rules-error').hide();

  //Is at least one condition block specified?
  var ctr=0;
  var submitForm=true;

  $('.condition-block').each(function (index) {
    if ($(this).hasClass('condition-active')) {
      //Bump class counter
      ctr++;
      //Remove any error messages for this condition
      $('#'+$(this).data('error-id')).hide();
      //If the related input for this control has no content, show error and quit now
      var control = "#"+$(this).data('input-control');
      if ($(control).val()=="") {
        $('#'+$(this).data('error-id')).show();
        submitForm=false;
      }
    }
  });

  //Submit form?
  if (!submitForm)
    return false;

  //No conditions active? Don't submit the control
  if (ctr==0) {
      $('#rules-error').show();
      return false;
  }

  return true;

}

//Form save
$('#form-save').click(function (e) {

  e.preventDefault();

  //Submit if the form is valid
  if ($('#edit-form').valid()) {
    if (validateRules()) {
      //Submit form
      $('#edit-form').submit();
    }
  }
});



</script>


@endsection
