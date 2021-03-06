<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarItem extends Component
{

    public $active;
    public $link;
    public $text;
    public $icon;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($link="", $icon="", $text="", $active=false)
    {
        $this->link = $link;
        $this->icon = $icon;
        $this->text = $text;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sidebar-item');
    }
}
