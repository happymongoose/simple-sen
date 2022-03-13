
<!-- Notes modal -->
<div class="modal fade" id="note-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="card">

          <!-- Card Header -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary" id="note-modal-title">Edit note</h6>

              <div id="note-delete-group" style="float: left">

                <div class="dropdown no-arrow">

                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                aria-labelledby="dropdownMenuLink">

                <!-- delete button -->
                <a class="dropdown-item" href="#" id="note-form-delete">Delete note</a>
                <a class="dropdown-item" href="#" id="note-form-own">Take ownership</a>
              </div>

            </div>
            <!-- end drop down menu -->

          </div>
          <!-- end dropdown -->

          </div>
          <!-- end card header -->

          <!-- Card Body -->
          <div class="card-body">

            {!! Form::open(['method' => 'PUT', 'id' => 'note-edit-form', 'name' => 'note-edit-form']) !!}
              <div id="note-routes" data-ownership-url="{{ $own_route }}" data-store-url="{{ $store_route }}" data-update-url="{{ $update_route }}" data-delete-url="{{ $delete_route }}"></div>

              <!-- Note title -->
              <div class="form-group">
                <label for="note-title">Title:</label>
                <input type="text" class="form-control" id="note-title"
                       name="note-title" placeholder="" value="" style="width: 100%">
               </div>
               <!-- end note title -->

               <!-- tags -->
               <div class="form-group">
                 <label for="note-title">Tags:</label>
                 <x-input-tag name="note-tags" id="note-tags"  tags="" />
               </div>
               <!-- end tags -->

               <!-- Note text -->
               <div class="form-group">
                 <label for="note-text">Text:</label>
                 <textarea class="form-control" id="note-text" name="note-text" placeholder="" style="width: 100%; height: 256px"></textarea>
              </div>
                <!-- end note text -->

              <!-- Note colour -->
              <div class="form-group" id="note-colour-group">
                <label for="note-colour">Colour</label>
                <div class="colour-picker-group input-group ml-1">
                  <input type="text" id="note-colour" name="note-colour" value="000000" style="display:none">
                  <span class="input-group-append">
                    <span class="input-group-text colorpicker-input-addon"><i></i></span>
                  </span>
                </div>
             </div>             
             <!-- end note colour -->

              <!-- Allow editing by other users -->
              @if ($note_class!="user")
              <div class="form-group" id="note-edit-group">
                <x-toggle-switch id="note-allow-edit" text="Allow other users to edit" />
              </div>
              @endif
              <!-- end allow editing -->

            <input type="hidden" name="note-class" value="{{ $parent_class }}">
            <input type="hidden" name="note-instance-id" value="{{ $instance_id }}">
            <input type="hidden" name="note-return-url" value="{{ $return_url }}" >
            <input type="hidden" name="note-action" value="add-update" >
            <input type="hidden" id="note-id" name="note-id" value="" >
            {!! Form::close() !!}

          </div>
          <!-- end card body -->

          <!-- Footer -->
          <div class="card-body" style="text-align: right">

            <!-- cancel button -->
            <a href="#" class="btn btn-secondary btn-icon-split" data-dismiss="modal">
              <span class="text">Cancel</span>
            </a>

            <!-- save button -->
            <a href="#" class="btn btn-primary btn-icon-split" id="note-form-save">
              <span class="icon text-white-50">
                <i class="fas fa-save"></i>
              </span>
              <span class="text">Save</span>
            </a>

          </div>
          <!-- end footer -->


      </div>
      <!-- end card -->

    </div>
  </div>
</div>
<!-- end modal -->
