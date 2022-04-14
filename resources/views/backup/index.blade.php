@extends('layouts.app')

@section('page-title')
Backups
@endsection

@section('styles')

<style>

#school-name {
  width: 95%;
  max-width: 800px;
}

</style>

@endsection

@section('content')

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Backups</h1>
    </div>

    <div class="col-sm-12">

      <!-- Show list of all teaching groups -->
      <div class="card shadow mb-4">

        <div class="card-body">

          <!-- Backup now button -->
          <div class="row">

            <div class="col-sm-12 col-lg-8">
              Backups will run every night at 2am. Older backups will be automatically removed over time.
            </div>
            <!-- end col -->
            <div class="col-sm-12 col-lg-4">
              <div class="text-right">
                <a href="{{ route('backup.run') }}" class="btn btn-success btn-icon-split mb-3">
                  <span class="icon text-white-50">
                    <i class="fas fa-save"></i>
                  </span>
                  <span class="text">Start a new backup</span>
                </a>
              </div>
            </div>
            <!-- end col -->

          </div>
          <!-- end row -->

          <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Backup Date</th>
                  <th><center>Action</center></th>
                </tr>
              </thead>
              <tbody>

                @foreach ($files as $file)

                <tr>

                  <td>{{ $file['date'] }}, {{ $file['time'] }}</td>

                  @if ($user->isAdmin())
                  <td class="text-center">

                    <a href="{{ route("backup.download", $file['id']) }}"
                      class="btn btn-icon text-gray-700 trigger-edit"
                      title="Download" data-toggle="tooltip" data-placement="top"
                    >
                      <i class="fas fa-download"></i>
                    </a>

                    <a href="{{ route("backup.restore", $file['id']) }}"
                      class="btn btn-icon text-gray-700 trigger-edit sa-trigger"
                      title="Restore from backup" data-toggle="tooltip" data-placement="top"

                      sa-title="Are you sure you want to restore from the backup taken on {{ $file['date']}}, {{ $file['time'] }}?",
                      sa-text="This may delete or overwrite information already in the database and you won't be able to undo this change.",
                      sa-icon="warning",
                      sa-show-cancel-button="true",
                      sa-confirm-text="Yes, restore from this backup",
                      sa-cancel-text="Cancel"
                    >
                      <i class="fas fa-undo-alt"></i>
                    </a>

                    <a href="{{ route('backup.delete', $file['id']) }}"
                      class="btn btn-icon text-gray-700 sa-trigger"
                      title="Delete" data-toggle="tooltip" data-placement="top"
                      sa-title="Are you sure you want to delete the backup from {{ $file['date']}}, {{ $file['time'] }}?",
                      sa-text="You won't be able to undo this change.",
                      sa-icon="warning",
                      sa-show-cancel-button="true",
                      sa-confirm-text="Yes, delete this backup",
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

  <x-table-footer :rows="$files" :max-rows="$max_results" />

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
