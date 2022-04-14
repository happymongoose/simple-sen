<?php

namespace App\Http\Controllers;

use App\Models\Registry;
use App\Helpers\Collection;
use App\Helpers\FunctionLibrary;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //Authorised users only
      $this->middleware('role:admin');

  }


  //--------------------------
  //
  // Returns an array with information about the back up files
  //
  //--------------------------

  private function getFiles() {

    //Get the registry
    $registry = new Registry;
    //Get the user's date format
    $date_format = $registry->getValue('date-format');


    //Get a list of all files in the backup directory
    $files_raw = array_reverse(Storage::disk('local')->files('simple-sen'));

    //Filter out none-backup files
    $files=[];
    $file_id = 0;
    foreach ($files_raw as $file_raw) {
      //Break the filename up into it's constituent parts
      $filename_parts = explode("/", $file_raw);
      //If the file is in a subdirectory (somehow), ignore it!
      if (count($filename_parts)!=2)
        continue;
      //If the file starts with a number and ends in ".zip", save it
      if ( (is_numeric($filename_parts[1][0])) && (str_ends_with($filename_parts[1], ".zip")) ) {

        //--------------------------
        //
        // Get useful info from filename
        //
        //--------------------------

        //Get the filename
        $filename = $filename_parts[1];

        //Get date
        $date_parts = explode("-", $filename);
        $date_string = $date_parts[0] . "-" . $date_parts[1] . "-" . $date_parts[2] . "-" . $date_parts[3] . "-" . $date_parts[4] . "-" . explode(".",$date_parts[5])[0];
        $date = \DateTime::createFromFormat("Y-m-d-H-i-s", $date_string);

        //Convert date into useful strings
        $date_string = $date->format($date_format);
        $time_string = $date->format("g.ia");
        $file_info = [
          "filename" => $filename,
          "date" => $date_string,
          "time" => $time_string,
          "id" => $file_id++,
        ];
        $files[] = $file_info;
      }
      //end if

    }
    //end foreach

    return $files;

  }

  //-----------------------------------------------------------------------------------
  //
  // Index page
  //
  //-----------------------------------------------------------------------------------


  public function index(Request $request) {

    //Get the current user
    $user=auth()->user();

    //Instantiate a function library
    $functionLibrary = new FunctionLibrary;

    //Get the maximum number of rows for the table
    $max_results = $functionLibrary->getTableMaxRows($request);

    //Get files
    $files = $this->getFiles();
    $files = (new Collection($files))->paginate($max_results);

    //Show view
    return view('backup.index', compact('user', 'files', 'request', 'max_results'));

  }

  //-----------------------------------------------------------------------------------
  //
  // Delete
  //
  //-----------------------------------------------------------------------------------

  public function delete($id) {

    //Get the array of backup files
    $files = $this->getFiles();

    //Delete the specified file
    foreach($files as $file) {
      if ($file['id']==$id) {
        Storage::delete('simple-sen/' . $file['filename']);
        notify()->success("Backup deleted");
        return redirect(route("backup.index"));
      }
    }

    notify()->error("Backup does not exist");
    return redirect(route("backup.index"));

  }

  //-----------------------------------------------------------------------------------
  //
  // Download
  //
  //-----------------------------------------------------------------------------------

  public function download($id) {

    //Get the array of backup files
    $files = $this->getFiles();

    //Delete the specified file
    foreach($files as $file) {
      if ($file['id']==$id) {
        return Storage::download('simple-sen/' . $file['filename']);
      }
    }

    notify()->error("Backup does not exist");
    return redirect(route("backup.index"));
  }

  //-----------------------------------------------------------------------------------
  //
  // Run backup
  //
  //-----------------------------------------------------------------------------------

  public function run() {

    //Tell artisan to start the backup
    Artisan::call("backup:run --only-db --disable-notifications");

    //Tell the user
    notify()->success("Backup initiated");
    return redirect(route("backup.index", ['backup-initiated'=>'true']))->withSuccess("Your backup has been started and may take a few minutes to complete. When it's finished, you'll see it in the list below.");

  }


  //--------------------------
  //
  // Decompresses as gz file
  //
  //--------------------------

  private function uncompressGZ($path) {

    // Raising this value may increase performance
    $buffer_size = 4096; // read 4kb at a time
    $out_file_name = str_replace('.gz', '', $path);

    // Open our files (in binary mode)
    $file = gzopen($path, 'rb');
    $out_file = fopen($out_file_name, 'wb');

    // Keep repeating until the end of the input file
    while (!gzeof($file)) {
        // Read buffer-size bytes
        // Both fwrite and gzread and binary-safe
        fwrite($out_file, gzread($file, $buffer_size));
    }

    // Files are done, close files
    fclose($out_file);
    gzclose($file);
  }

  //-----------------------------------------------------------------------------------
  //
  // Restore from back up
  //
  //-----------------------------------------------------------------------------------

  public function restore($id) {

    //Get the array of backup files
    $files = $this->getFiles();

    //Delete the specified file
    $zip_path=null;
    foreach($files as $file) {
      if ($file['id']==$id) {
        $zip_path = Storage::disk('local')->path("simple-sen/" . $file['filename']);
        break;
      }
    }

    //If the file wasn't found, quit now
    if ($zip_path==null) {
      notify()->error("Backup does not exist");
      return redirect(route("backup.index"));
    }

    //Unzip the archive into a temporary directory
    $zip = new \ZipArchive;
    if ($zip->open($zip_path)) {
     $zip->extractTo(Storage::path('backup-temp'));
     $zip->close();
    }

    //The sql file is also compressed using gz - decompress it
    $this->uncompressGZ(Storage::path('backup-temp/db-dumps/mysql-simple_sen.sql.gz'));

    //Get path to final sql file
    $path = '"' . Storage::path('backup-temp/db-dumps/mysql-simple_sen.sql') . '"';

    //Restore database - let the backup run for up to 300s
    $command = "mysql -u " . env("DB_USERNAME") . " -p" . env("DB_PASSWORD") . " -h " . env("DB_CONNECTION") . " " . env("DB_DATABASE") . " < " . $path;
//    echo $command;
    $process = Process::fromShellCommandline($command, null, null, null, 300);
    $process->run();

    //Tidy up
    $files = Storage::disk('local')->files('backup-temp/db-dumps');
    //Remove archived and gz files
    foreach ($files as $file)
      Storage::disk('local')->delete($file);
    //Remove temporary working directory
    Storage::disk('local')->deleteDirectory('backup-temp/db-dumps');

    //Tell the user
    notify()->success("Data restored");
    return redirect(route("backup.index"));
  }

}
