<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebugTable extends Model
{
    use HasFactory;

    //Outputs a debug table
    //$data = [
    //  'headings' => [heading 1, heading 2, heading 3],
    //  'data' => [ data 1, data 2, data 3]
    //]
    public function output($data) {

?>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary"><?php echo $data['title']; ?></h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <?php
          foreach($data['headings'] as $heading) {
            echo "<th>" . $heading . "</th>";
          }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach($data['rows'] as $row) {
      ?>
      <tr>
        <?php
        foreach($row as $value) {
          echo "<td>" . $value . "</td>";
        } //endfor values
      ?>
      </tr>
      <?php
      } //endfor row
      ?>
    </tbody>
  </table>

</div>
</div>
</div>

<?php


    }



}
