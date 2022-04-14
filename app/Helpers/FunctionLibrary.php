<?php

namespace App\Helpers;

use Cookie;
use Illuminate\Http\Request;

class FunctionLibrary {

  //--------------------------
  //
  // Returns the given value, of the default value, if the first parameter is null
  //
  //--------------------------

  public function getValue($a, $default) {
    if ($a==null)
      return $default;
    return $a;
  }


  //--------------------------
  //
  // Returns the maximum number of rows for a data table
  //
  //--------------------------

  public function getTableMaxRows(Request $request) {

    //Get the pagination length (if set)
    $max_results = $request->input("max_results", null);

    //If a number of rows was specified in the URL
    if ($max_results!=null) {
      //Save new value
      Cookie::queue('tables_default_row_count', $max_results);
      //Return it
      return $max_results;
    }

    //Use the value in the user's cookie, or a hard default of 30 rows
    return $this->getValue($request->cookie('tables_default_row_count'), 30);

  }


}
