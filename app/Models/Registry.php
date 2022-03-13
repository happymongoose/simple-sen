<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DebugTable;

class Registry extends Model
{
    use HasFactory;

    //Specify table name
    protected $table = "registry";

    //Fillable fields
    protected $fillable = [
        'key',
        'value',
        'type',
    ];


    //Table for converting 'friendly' types to shorter char abbreviations
    private $typeConversion = [
      'boolean' => 'b',
      'integer' => 'i',
      'float' => 'f',
      'string' => 's'
    ];

    //Cast value going into or out of registry
    private function cast($value, $type) {

      switch ($type) {

        case 'b':
          return (bool) $value=="1";
          break;

        case 'i':
          return intval($value);
          break;

        case 'f':
          return floatval($value);
          break;

        case 's':
        default:
          return $value;

      }

    }

    //Converts a type defined in one char as a piece of friendly text
    private function convertTypetoFriendly($type) {

      //Find the friendly text in the conversion array
      $friendly = array_search($type, $this->typeConversion);

      //If not found, throw error
      if (!$friendly) {
        throw new \Exception("Illegal variable type '" . $type .  "' passed to Registry->convertTypetoFriendly");
      }

      //Return the text
      return $friendly;

    }


    //Returns the registry item for given key
    public function get($key) {

      //Grab item for key (if it exists)
      $item = Registry::firstWhere('key', $key);

      //If key doesn't exist, return null
      if ($item==null)
        return null;

      return $item;
    }

    //Returns the value of a given key
    public function getValue($key, $default=null) {

      //Get item
      $item = $this->get($key);

      //If doesn't exist, return null
      if ($item==null)
        return $default;

      //Cast value and return
      return $this->cast($item->value, $item->type);
    }


    //Sets value for given key
    public function set($key, $value, $friendly_type="string") {

      //Check the passed type is valid
      if (!isset($this->typeConversion[$friendly_type])) {
        throw new \Exception("Illegal variable type '" . $friendly_type .  "' passed to Registry->set");
      }

      //Convert the type variable in a friendly format (string, integer, float) etc. into one char abbreviation
      $type = $this->typeConversion[$friendly_type];

      //Check if the key already exists.
      $item = Registry::firstWhere('key', $key);

      //Recast item
//      $value = $this->cast($value, $type);

      //If the item already exists, change the value
      if ($item!=null) {
        $item->value = $value;
        $item->type = $type;
        $item->save();
      } else {
        //Otherwise create it
        $item = new Registry();
        $item->fill([
          'key' => $key,
          'value' => $value,
          'type' => $type
        ]);
        $item->save();
      }

      return true;

    }

    //Outputs debug information for the given registry key
    public function debugKey($key) {

      //Get item from registry
      $item = $this->get($key);

      //If key doesn't exist, report that
      if ($item==null) {
        echo "Doesn't exist.<br/>";
        return;
      }

      //Get the type as friendly text
      $friendly_type = $this->convertTypetoFriendly($item->type);

      //Cast value
      $cast_value = $this->cast($item->value, $item->type);

      //Report key values
      $debugTable= new DebugTable();
      $debugTable->output([
        'title' => "Registry debug information",
        'headings' => ['Key', 'True value', 'Returned value', 'Type'],
        'rows' => [
          [$item->key, $item->value, $cast_value, $friendly_type]
        ]
      ]);

    }

    //Outputs debug information for entire registry
    public function debug() {

      //Get all items
      $items = Registry::all();

      //Debug table info
      $debugTableData = [
          'title' => 'Registry debug information',
          'headings' => ['Key', 'True value', 'Returned value', 'Type'],
          'rows' => []
      ];

      //Iterate over items and add them to debug table
      foreach ($items as $item) {
        //Get the type as friendly text
        $friendly_type = $this->convertTypetoFriendly($item->type);
        //Cast value
        $cast_value = $this->cast($item->value, $item->type);
        //Add to debug table data
        $debugTableData['rows'][] = [$item->key, $item->value, $cast_value, $friendly_type];
      }

      //Output debug table
      $debugTable= new DebugTable();
      $debugTable->output($debugTableData);

    }

}
