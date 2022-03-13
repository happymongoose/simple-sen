
<!-- notes -->
<div class="card shadow mb-4 ml-2 mr-2">

  <!-- Title -->
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
  </div>

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
