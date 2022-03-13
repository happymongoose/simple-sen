@extends('layouts.app')

@section('page-title')
View class
@endsection

@section('content')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $group->name }}</h1>
    </div>


    <div class="col-sm-12">

      <!-- Show list of all students -->
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

          <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  @if ($user->isAdmin())
                  <th class="text-center">Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>

                @foreach ($students as $student)
                <tr>

                  <td class="table-cell-clickable" href="{{ route('students.edit', $student->id) }}">{{ $student->getFullName() }}</td>

                  @if ($user->isAdmin())
                  <td class="text-center">

                    <a href="{{ route('students.delete', $student->id ) }}?return-route={{ Request::fullUrl() }}"
                      class="btn btn-icon text-gray-700 sa-trigger"
                      title="Delete" data-toggle="tooltip" data-placement="top"
                      sa-title="Are you sure you want to delete {{ $student->getFullName() }}?",
                      sa-text="You won't be able to undo this change, and any data for this student will be deleted forever.",
                      sa-icon="warning",
                      sa-show-cancel-button="true",
                      sa-confirm-text="Yes, delete this student",
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

  <x-table-footer :rows="$students" :max-rows="$max_results" />

@endsection

@section('scripts')


@endsection
