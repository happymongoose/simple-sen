@extends('layouts.app')

@section('page-title')
My notes
@endsection

@section('stylesheets')

@endsection

@section('styles')

@endsection


@section('content')

<x-note-modal instance-identifier="{{ $user->id }}" parent-class="user" update-route="{{ route('notes.update') }}" store-route="{{ route('notes.store') }}" return-route="{{ Request::fullUrl() }}" delete-route="{{ route('notes.delete') }}"  own-route="{{ route('notes.own') }}" note-class="user" />

  <div class="row">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My notes</h1>
    </div>

    <!-- notes -->
    <div class="card shadow mb-4 ml-2 mr-2">

      <div class="card-body">

        <div class="row">

          <!-- search bar -->
          <div class="col-sm-6">
            <form
            class="d-none d-sm-inline-block form-inline mr-auto navbar-search mb-3"
            style="width:95%" id="search-form">
            <div class="input-group">
              <input
              type="text"
              class="form-control bg-light border-0 small"
              placeholder="Search notes that have all these tags..."
              aria-label="Search" aria-describedby="basic-addon2"
              value="{{ Request::input('note-search') }}"
              name="note-search"
              id="note-search-text"
              >
              <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="search-button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
        </div>

        <!-- new note -->
        <div class="col-sm-6 text-right">

          <a href="#" id="new-note-button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> New note</a>

          </div>

        </div>

        <div class="row" style="max-height: 100vh; overflow-y: scroll">

          <div class="col-sm-12">
            <x-notes :notes="$notes"  />
          </div>

        </div>
        <!-- end row -->

      </div>
      <!-- end card body -->

    </div>
    <!-- end card -->

  </div>
  <!-- end row -->

  <x-table-footer :rows="$notes" :max-rows="$max_results" />


@endsection


@section('scripts')

<script src="{{ asset("js/tagger.js") }}"></script>
<script src="{{ asset("js/notes.js") }}"></script>

<script>

//--------------------------
//
// Tag editor
//
//--------------------------

var all_tags = [{!! $all_tags_as_string !!}];

//--------------------------
//
// Note search
//
//--------------------------

$('#note-search-button').click(function(e) {
  e.preventDefault();
  $('#search-form').submit();
});

notesInit(all_tags);

//Update the table length and reload
$('#table_length').change(function () {
    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set("max_results", $('#table_length').val());
    window.location.search = searchParams.toString();
})

</script>


@endsection
