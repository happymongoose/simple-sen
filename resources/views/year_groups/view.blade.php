@extends('layouts.app')

@section('page-title')
{{ $group->name }}
@endsection

@section('content')


  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $group->name }}</h1>
    </div>


    <div class="col-sm-12">

      <!-- Show list of all teaching groups -->
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
                      placeholder="Search by name or tag..."
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


        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th class="text-center">Class</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($students as $student)

                <tr>

                  <td class="table-cell-clickable trigger-view" href="{{ route("students.edit", [$student->id]) }}">{{ $student->getFullName() }}</td>
                  <td class="table-cell-clickable trigger-view text-center" href="{{ route("students.edit", [$student->id]) }}">{{ $student->teaching_group->name }}</td>

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

  <x-table-footer :rows="$students" :max-rows="$max_results" />

@endsection

@section('scripts')

<script>

$('#search-button').click(function(e) {
    e.preventDefault();
    $('#search-form').submit();
});

//Update the table length and reload
$('#table_length').change(function () {
    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set("max_results", $('#table_length').val());
    window.location.search = searchParams.toString();
});

</script>


@endsection
