<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Note extends Component
{

    public $note;
    public $link;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($note, $link)
    {
        $this->note = $note;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.note');
    }
}
