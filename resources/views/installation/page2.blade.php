@extends('layouts.installation')

@section('page-title')
Simple SEN installation
@endsection

@section('stylesheets')

<style>

#school-name {
  width: 95%;
  max-width: 800px;
}
</style>


@endsection


@section('content')

<?php /*  <div class="row mt-4" style="height: 100vh" style="background-color:orange"> */ ?>

  <!-- Outer Row -->
  <div class="row justify-content-center" style="min-width: 100%;">

    <div class="col-sm-12 col-md-10 col-lg-8 col-xl-8">

      <!-- Show list of all students -->
      <div class="card shadow mb-4">


        <div class="card-header py-3">
          <img src="{{ asset("img/simple-sen-logo.svg") }}" class="installation-logo"/>
        </div>

        {!! Form::open(['method' => 'GET', 'id' => 'installation-form', 'name' => 'installation-form', 'route' => 'install_page3']) !!}

        <div class="card-body">

            <h1 class="mb-3 font-weight-bold">Your school</h1>
            <p class="pb-0">What is the name of your school?</p>

            <div class="form-group">
              {!! Form::text('school-name', '', array('id'=>'school-name')) !!}
              <span class="invalid-feedback" id="name-error">
                  <strong>Please enter a value</strong>
              </span>
            </div>
            <!-- end col -->


          </div>
          <!-- end card body -->

          <!-- footer -->
          <div class="card-footer" style="text-align: right">
            <button type="button" class="btn btn-primary" id="form-next">Next</button>
          </div>
          <!-- end footer -->

          </form>

          </div>
          <!-- end row -->

        </div>
        <!-- end card body -->

      </div>
      <!-- end card -->

    </div>
    <!-- end col -->


  </div>
  <!-- end row -->

@endsection

@section('scripts')

<script>

$('#form-next').click(function () {

  //Check the school name text box contains something
  var school_name = $('#school-name').val().trim();
  if (school_name=="") {
    $('#name-error').show();
    return;
  }
  //Hide error message
  $('#name-error').hide();

  //Submit form
  $('#installation-form').submit();

});

</script>


@endsection
