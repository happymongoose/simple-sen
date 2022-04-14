<!-- pagination -->
<div class="row">

  <div class="col-sm-12 pb-5">

    <div class="d-flex justify-content-between">
      <!-- Empty div -->
      <div></div>
      <!-- Pagination links -->
      <div>
      {!! $rows->links() !!}
      </div>
      <!-- Results per page -->
      <div>
        Results per page:  {{ Form::select('table_length', ['20'=>'20', '30'=>'30', '50'=>'50','100'=>'100'], $maxRows, array('id' => 'table_length', 'class' => 'table-dropdown')) }}
      </div>
    </div>
  </div>

</div>
<!-- end row -->
