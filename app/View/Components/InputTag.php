<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Tag;

class InputTag extends Component
{
    public $tags;
    public $tagList;
    public $name;
    public $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($tags, $name, $id, $colourTable=null)
    {
        //Get pre-existing tags
        $this->tags = $tags;
        $this->name = $name;
        $this->id = $id;

        //Create a table of all tags
          //Grab all tags
          $allTags = Tag::all();
          //Create a table to hold values
          $tagTable = [];
          //Build table in form "text:colour"
          if ($colourTable==null) {
            foreach($allTags as $tag) {
              $tagTable[] = $tag->tag . ":#" . $tag->colour;
            }
          //Implode table into a text string
          $this->tagString = implode(",", $tagTable);
        } else {
          $this->tagString = $colourTable;
        }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $tagString = $this->tagString;
        return view('components.input-tag', compact('tagString'));
    }
}
