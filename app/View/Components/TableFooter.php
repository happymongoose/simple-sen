<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableFooter extends Component
{
    public $rows;
    public $maxRows;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($rows, $maxRows)
    {
        $this->rows = $rows;
        $this->maxRows = $maxRows;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table-footer');
    }
}
