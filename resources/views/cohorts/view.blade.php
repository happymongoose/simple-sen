@extends('layouts.app')

@section('page-title')
{{ $cohort->description }}
@endsection

@section('content')


@include('teaching_groups.partials.modal')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $cohort->description }}</h1>
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
          <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th class="text-center">Class</th>
                  <th class="text-center">Year group</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($cohort->paginatedStudents as $student)

                <tr>

                  <td class="table-cell-clickable trigger-view" href="{{ route("students.edit", [$student->id]) }}">{{ $student->getFullName() }}</td>
                  <td class="table-cell-clickable trigger-view text-center" href="{{ route("students.edit", [$student->id]) }}">{{ $student->teaching_group->name }}</td>
                  <td class="table-cell-clickable trigger-view text-center" href="{{ route("students.edit", [$student->id]) }}">{{ $student->year->name }}</td>

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

  <x-table-footer :rows="$cohort->paginatedStudents" :max-rows="$max_results" />

@endsection

@section('scripts')

<script>

//Update the table length and reload
$('#table_length').change(function () {
    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set("max_results", $('#table_length').val());
    window.location.search = searchParams.toString();
});

</script>


@endsection
