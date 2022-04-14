
<!-- Modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">


      <!-- Header -->
      <div class="card">

          <!-- Card Header -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary" id="modal-title">Edit tag</h6>
          </div>
          <!-- end card header -->

          <!-- Card Body -->
          <div class="card-body">

            {!! Form::open(['route' => ['tags.update'], 'method' => 'PUT', 'id' => 'edit-form', 'name' => 'edit-form']) !!}
            <div id="tag-data" data-student-count="0"></div>

              <!-- Tag text -->
              <div class="form-group">
                <label for="text">Tag:</label>
                <input type="text" class="form-control" id="tag"
                       name="tag" placeholder="" value="">
               </div>
               <!-- Tag description -->
               <div class="form-group">
                 <label for="description">Description:</label>
                 <input type="text" class="form-control" id="description"
                        name="description" placeholder="" value="">
              </div>
              <!-- Tag colour -->
              <div class="form-group" id="colour-group">
                <label for="description">Colour:</label>
                <div class="input-group ml-1">
                  <input type="text" id="colour" name="colour" value="000000" style="display:none">
                  <span class="input-group-append">
                    <span class="input-group-text colorpicker-input-addon"><i></i></span>
                  </span>
                </div>
             </div>
              <input type="hidden" value="" id="id" name="id">
              <input type="hidden" value="" id="initial-tag" name="initial-tag">
            {!! Form::close() !!}

          </div>
          <!-- end card body -->

          <!-- footer -->
          <div class="card-body" style="text-align: right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="form-save">Save</button>
          </div>
          <!-- end footer -->

        </div>
        <!-- end card -->

    </div>
  </div>
</div>
