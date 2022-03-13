<?php

namespace App\Http\Controllers;

use App\Models\YearGroup;
use Illuminate\Http\Request;

class YearGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\YearGroup  $yearGroup
     * @return \Illuminate\Http\Response
     */
    public function show(YearGroup $yearGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\YearGroup  $yearGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(YearGroup $yearGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\YearGroup  $yearGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YearGroup $yearGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\YearGroup  $yearGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(YearGroup $yearGroup)
    {
        //
    }


    //-----------------------------------------
    //
    // Returns all the year groups as a string
    //
    //-----------------------------------------

    public function getAllAsString() {

      $yearGroups = YearGroup::all()->sortBy('year');
      //Create a simple table from the year groups
      $yearGroupTable = [];
      foreach ($yearGroups as $yearGroup) {
        $yearGroupTable[] = "'" . $yearGroup->name . "'";
      }
      //Return the table as a string
      return implode(",", $yearGroupTable);
    }


}
