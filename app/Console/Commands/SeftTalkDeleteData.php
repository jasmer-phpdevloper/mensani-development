<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use File;
use Carbon\Carbon;


class SeftTalkDeleteData extends Command
{
    protected $signature = 'start_selftalks:PreviousDatadelete';
    protected $description = 'Deletes files and data before a specific date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Delete files logic
        set_time_limit(300);  
        $directory = storage_path('athletevoice'); // Replace with the actual directory path where files are stored
       
         // Specify the date condition
         $deleteDate = Carbon::yesterday(); // Replace with the desired date condition
     
         // Get all files in the directory
         $files = File::files($directory);
     
         // Loop through the files and delete based on the date condition
         foreach ($files as $file) {
           if (time() - $_SERVER['REQUEST_TIME_FLOAT'] > 110) {
             break;
           }
             $fileDate = Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d');
             if ($fileDate < $deleteDate->format('Y-m-d')) {
                 File::delete($file);
             }
         }

        // Delete table data logic
        $tableName = 'start_selftalks'; // Replace with the actual table name
        $deleteDate = now()->subDay(); // Change this to the desired date limit
        DB::table($tableName)->where('created_at', '<', $deleteDate)->delete();
        DB::table('visualizations')->where('created_at', '<', $deleteDate)->delete();
        DB::table('dammy_table')->insert(['name'=> "start_selftalks"]);
        DB::table('dammy_table')->insert(['name'=> "visualizations"]);
     

        $this->info('Files and data deleted successfully.');
    }









}
