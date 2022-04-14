<?php

namespace App\View\Components;

use Illuminate\View\Component;

class YearGroupDropdown extends Component
{

    public $yearGroups;
    public $default;
    public $showLabel;
    public $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($yearGroups, $default=null, $showLabel="true", $id="year-group")
    {
        //Create array from set
        $this->yearGroups=[];
        foreach ($yearGroups as $yearGroup) {
          //If showing this year group, add to list
          if ($yearGroup->show)
            $this->yearGroups[$yearGroup->year] = $yearGroup->name;
        }
        $this->default = $default;
        $showLabel = $showLabel=="true";
        $this->showLabel = $showLabel;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.year-group-dropdown');
    }
}
