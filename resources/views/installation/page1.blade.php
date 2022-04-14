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

        {!! Form::open(['method' => 'GET', 'id' => 'installation-form', 'name' => 'installation-form', 'route' => 'install_page2']) !!}

        <div class="card-body">

            <h1 class="mb-3 font-weight-bold">Hello you... and welcome to Simple SEN! 😀</h1>
            <p class="pb-2 installation-header">Would you like some help getting started?</p>
            <p class="pb-2">By answering a small number of questions, I can get you up and running with settings that will work for most schools.</p>

            <div class="form-group">

              <p>Does that work for you?</p>
              <input type="radio" value="yes" checked id="installation_help_yes" name="installation_help">
              <label for="installation_help_yes">Sounds good... let's do this!</label><br/>

              <input type="radio" value="no" id="installation_help_no" name="installation_help">
              <label for="installation_help_no">No thanks... I'm happy setting everything up by myself.</label>

            </div>
            <!-- end form group -->

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
  $('#installation-form').submit();
});

</script>


@endsection
