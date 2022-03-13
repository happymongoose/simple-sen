@extends('layouts.app')

@section('page-title')
Classes
@endsection

@section('content')

<?php
//TODO: Send multiple students to new class.
?>

  <div class="row">

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
                  <th data-field="name" data-type="text" data-sortable="true">Name</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($students as $student)
                <tr>


                  <td class="table-cell-clickable" href="{{ route('students.edit', $student->id) }}">{{ $student->getFullName() }}</td>

                  <td class="text-center">

                    <a href="{{ route('students.edit', $student) }}"
                      class="btn btn-icon text-gray-700"
                      title="View" data-toggle="tooltip" data-placement="top">
                      <i class="fas fa-eye"></i>
                    </a>

                    @if ($group->id!=1)

                    <a href="{{ route('teaching_groups.remove_student', [$group->id, $student->id]) }}"
                      class="btn btn-icon text-gray-700 sa-trigger"
                      title="Remove" data-toggle="tooltip" data-placement="top"
                      sa-title="Remove '{{ $student->getFullName() }}' from this class?",
                      sa-text="They'll be placed in the 'unallocated' class.",
                      sa-icon="warning",
                      sa-show-cancel-button="true",
                      sa-confirm-text="Yes, remove this student",
                      sa-cancel-text="Cancel"
                    >
                      <i class="fas fa-user-slash"></i>
                    </a>

                    @endif

                  </td>

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

@endsection

@section('scripts')


@endsection
