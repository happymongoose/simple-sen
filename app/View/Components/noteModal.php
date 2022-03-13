<?php

namespace App\View\Components;

use Illuminate\View\Component;

class noteModal extends Component
{

    public $parent_class;
    public $instance_id;
    public $update_route;
    public $store_route;
    public $return_url;
    public $delete_route;
    public $own_route;
    public $note_class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($parentClass, $instanceIdentifier, $updateRoute, $storeRoute, $returnRoute, $deleteRoute, $ownRoute, $noteClass="")
    {
        $this->parent_class = $parentClass;
        $this->instance_id = $instanceIdentifier;
        $this->update_route = $updateRoute;
        $this->store_route = $storeRoute;
        $this->return_url = $returnRoute;
        $this->delete_route = $deleteRoute;
        $this->own_route = $ownRoute;
        $this->note_class = $noteClass;
        
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.note-modal');
    }
}
