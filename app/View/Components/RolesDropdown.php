<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RolesDropdown extends Component
{
    public $showLabel;
    public $roles;
    public $default;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($roles, $showLabel=true, $default=1)
    {
        $this->showLabel = $showLabel;
        $this->default = $default;
        //Create array from set
        $this->roles=[];
        foreach ($roles as $role) {
          $this->roles[$role->role_id] = $role->name;
        }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.roles-dropdown');
    }
}
