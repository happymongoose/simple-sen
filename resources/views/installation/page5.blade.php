@extends('layouts.installation')

@section('page-title')
Simple SEN installation
@endsection

@section('stylesheets')

<style>
#next-steps {
  list-style-type: circle;
  color: black;
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

        <div class="card-body">

            <h1 class="mb-3 font-weight-bold">We're all done!</h1>
            <p class="mb-1">The next thing you'll want to do is:</p>
            <ul id="next-steps">
              <li>> Enter the names of the classes you have in your school</li>
              <li>> Set up logins for additional adults in school</li>
              <li>> Enter the names of your students</li>
            <ul>

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
  window.location="{{ route('home') }}";
});

</script>


@endsection
