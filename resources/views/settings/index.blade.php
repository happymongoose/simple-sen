@extends('layouts.app')

@section('page-title')
Settings
@endsection

@section('styles')

<style>

#school-name {
  width: 95%;
  max-width: 800px;
}

</style>

@endsection

@section('content')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Settings</h1>
    </div>

    {!! Form::open(['method' => 'POST', 'id' => 'settings-form', 'name' => 'settings-form', 'route' => 'settings.update']) !!}

    <div class="col-sm-12">

      <!-- Show list of all teaching groups -->
      <div class="card shadow mb-4">

        <div class="card-body">

          <div class="form-group">

            <label for="school-name">School name:</label>
            <input type="text" class="form-control" id="school-name"
                   name="school-name" placeholder="" value="{{ $registry->getValue("school-name", "")}}" style="width: 100%">

          </div>
          <!-- end form group -->


        </div>
        <!-- end card body -->

        <!-- Footer -->
        <div class="card-body" style="text-align: right">

          <!-- save button -->
          <a href="#" class="btn btn-primary btn-icon-split" id="form-save">
            <span class="icon text-white-50">
              <i class="fas fa-save"></i>
            </span>
            <span class="text">Save</span>
          </a>

        </div>
        <!-- end footer -->


      </div>
      <!-- end card -->

    </div>
    <!-- end col -->

    </form>

  </div>
  <!-- end row -->

@endsection

@section('scripts')

<script>


//--------------------------
//
// Validation
//
//--------------------------

//Input validator
var validator;

$(document).ready(function () {
  validator = $('#settings-form').validate({
      rules: {
          "school-name": {
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
  if ($('#settings-form').valid()) {
    $('#settings-form').submit();
  }
});

</script>


@endsection
