@extends('layouts.app')

@section('page-title')
Cohorts
@endsection

@section('content')


@include('teaching_groups.partials.modal')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cohorts</h1>
        @if ($user->isAdmin())
        <a href="{{ route('cohorts.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> New cohort</a>
        @endif
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
                  <th>Description</th>
                  <th class="text-center">Students</th>
                  @if ($user->isAdmin())
                  <th class="text-center">Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>

                @foreach ($cohorts as $cohort)

                <tr>

                  <td class="table-cell-clickable trigger-view" href="{{ route("cohorts.view", [$cohort->id]) }}">{{ $cohort->description }}</td>
                  <td class="table-cell-clickable trigger-view text-center" href="{{ route("cohorts.view", [$cohort->id]) }}">{{ count($cohort->students) }}</td>

                  @if ($user->isAdmin())
                  <!-- Admin only editing area -->
                  <td class="text-center">

                    <a href="{{ route('cohorts.edit', $cohort->id) }}"
                      class="btn btn-icon text-gray-700"
                      title="Edit cohort" data-toggle="tooltip" data-placement="top"
                    >
                      <i class="fas fa-edit"></i>
                    </a>

                    <a href="{{ route('cohorts.delete', $cohort->id) }}"
                      class="btn btn-icon text-gray-700 sa-trigger"
                      title="Delete" data-toggle="tooltip" data-placement="top"
                      sa-title="Are you sure you want to delete cohort '{{ $cohort->description }}'?",
                      sa-text="No actual student data will be deleted - just the description of the cohort.",
                      sa-icon="warning",
                      sa-show-cancel-button="true",
                      sa-confirm-text="Yes, delete this cohort",
                      sa-cancel-text="Cancel"
                    >
                      <i class="fas fa-trash-alt"></i>
                    </a>

                  </td>
                  @endif

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

  <x-table-footer :rows="$cohorts" :max-rows="$max_results" />

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
