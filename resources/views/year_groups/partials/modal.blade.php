
<!-- Modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="card">
          <!-- Card Header -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary" id="modal-title">Edit year group</h6>
          </div>
      </div>

      <!-- Card Body -->
      <div class="card-body">
        {!! Form::open(['route' => ['year_groups.update'], 'method' => 'PUT', 'id' => 'edit-form', 'name' => 'edit-form']) !!}
          <!-- Class name -->
          <div class="form-group">
            <label for="text">Name:</label>
            <input type="text" class="form-control" id="year-group-name"
                   name="year-group-name" placeholder="" value="">
           </div>

           <div class="form-group">
             <x-toggle-switch id="year-group-show" text="In use? (Show year group in menus etc.)" />
           </div>

          <input type="hidden" value="" id="id" name="id">
        {!! Form::close() !!}
      </div>

      <!-- card footer -->
      <div class="card-body"  style="text-align: right">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="form-save">Save</button>
      </div>
      <!-- end footer -->

    </div>
  </div>
</div>
