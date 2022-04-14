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

        {!! Form::open(['method' => 'GET', 'id' => 'installation-form', 'name' => 'installation-form', 'route' => 'install_page5']) !!}

        <div class="card-body">

            <h1 class="mb-3 font-weight-bold">Tags</h1>
            <p class="pb-2">Would you like me to set you up with some common tags for grouping cohorts of students?</p>

            <div class="form-group">

              <input type="radio" value="yes" checked id="tags_yes" name="tags">
              <label for="tags_yes">Yes, please</label><br/>

              <input type="radio" value="no" id="tags_no" name="tags">
              <label for="tags_no">No thanks</label>

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
