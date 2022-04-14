@extends('layouts.installation')

@section('page-title')
Simple SEN installation
@endsection

@section('stylesheets')


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

        {!! Form::open(['method' => 'GET', 'id' => 'installation-form', 'name' => 'installation-form', 'route' => 'install_page4']) !!}

        <div class="card-body">

            <h1 class="mb-3 font-weight-bold">Age range</h1>
            <p class="pb-2">What is the age range of the children you have in school?</p>

              <div class="row">

                <div class="col-sm-12 col-md-12 col-lg-6">
                  <div class="form-group">
                    <label for="age-low">Youngest year group</label><br/>
                    <x-year-group-dropdown :year-groups="$year_groups" show-label="false" id="age-low"/>
                  </div>
                </div>
                <!-- end col -->

                <div class="col-sm-12 col-md-12 col-lg-6">
                  <div class="form-group">
                    <label for="age-high">Oldest year group</label><br/>
                    <x-year-group-dropdown :year-groups="$year_groups" show-label="false" id="age-high"/>
                  </div>
                </div>
                <!-- end col -->

              </div>
              <!-- end row -->

              <span class="invalid-feedback" id="age-error">
                  <strong>Maximum age group must be older than the youngest year group</strong>
              </span>

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

  //Check the oldest year group is older than the youngest one
  var youngest = parseInt($("#age-low").val());
  var oldest = parseInt($('#age-high').val());
  if (youngest>=oldest) {
    $('#age-error').show();
    return;
  }

  //Hide error message
  $('#age-error').hide();

  //Submit form
  $('#installation-form').submit();

});

</script>


@endsection
