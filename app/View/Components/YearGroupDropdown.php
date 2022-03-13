<?php

namespace App\View\Components;

use Illuminate\View\Component;

class YearGroupDropdown extends Component
{

    public $yearGroups;
    public $default;
    public $showLabel;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($yearGroups, $default=null, $showLabel=true)
    {
        //Create array from set
        $this->yearGroups=[];
        foreach ($yearGroups as $yearGroup) {
          $this->yearGroups[$yearGroup->year] = $yearGroup->name;
        }
        $this->default = $default;
        $this->showLabel = $showLabel;
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
