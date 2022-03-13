<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TeachingGroupDropdown extends Component
{

    public $teaching_groups;
    public $default="";
    public $showLabel;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($teachingGroups, $default, $showLabel=false)
    {
      //Create array from set
      $this->teaching_groups=[];
      foreach ($teachingGroups as $teachingGroup) {
        $this->teaching_groups[$teachingGroup->id] = $teachingGroup->name;
      }
      $this->default = $default;
      $this->showLabel = $showLabel == "true";
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.teaching-group-dropdown');
    }
}
