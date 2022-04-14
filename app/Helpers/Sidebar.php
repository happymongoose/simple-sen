<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Model
{
  use HasFactory;

  public $items;
  public $title;
  public $groupID;
  public $type;

  private $user;

  public function __construct($title="") {
    $this->items = array();
    $this->title = $title;
    $this->groupID = str_replace(" ", "-", "collapse-sidebar-" . strtolower($title));
    $this->type = "group";
    $this->user = Auth::user();

    //Create sidebar groups for all users
    $this->addItem(route("students.index"), "Students", "fa-user");
    $this->addItem(route("teaching_groups.index"), "Classes", "fa-users");
    $this->addItem(route("notes.user.index"), "My notes", "fa-sticky-note");
    $this->addItem(route("cohorts.index"), "Cohorts", "fa-layer-group");
    $this->addDivider();

    //Create sidebar items for admin only
    if ($this->user->isAdmin()) {
      $this->addItem(route("tags.index"), "Manage tags", "fa-tags");
      $this->addItem(route("users.index"), "Manage adults", "fa-chalkboard-teacher");
      $this->addItem(route("year_groups.index"), "Manage year groups", "fa-calendar");
      $this->addItem(route("settings.index"), "Settings", "fa-cog");
      $this->addItem(route("backup.index"), "Backups", "fa-hdd");
    }

  }

  public function addHeading($text) {
    //Create object to hold information
    $item = new \stdClass();
    //Enter information
    $item->type = "heading";
    $item->text = $text;
    //Add to list of items
    $this->items[] = $item;
  }

  //Add item
  public function addItem($link, $text, $icon=null) {
    //Create object to hold information
    $item = new \stdClass();
    //Enter information
    $item->type = "item";
    $item->link = $link;
    $item->text = $text;
    $item->icon = $icon;
    //Add to list of items
    $this->items[] = $item;
  }

  //Add divider
  public function addDivider() {
    //Create object to hold information
    $item = new \stdClass();
    //Enter information
    $item->type = "divider";
    //Add to list of items
    $this->items[] = $item;
  }

  //Add group
  public function addGroup($group) {
    //Add to list of items
    $this->items[] = $group;
  }


}
