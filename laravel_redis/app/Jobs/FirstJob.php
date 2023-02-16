<?php

namespace App\Jobs;

use App\Models\Post; 
use DB;

class FirstJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
 
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {  
        DB::beginTransaction();
        
        try {

            DB::table('failed_jobs')->insert([ 
                'connection'=>'FirstJob',
                'queue'=>'',
                'payload'=>'', 
                'exception'=>'',
                'failed_at'=>date("Y-m-d H:i:s")
            ]);

            DB::commit();
        } catch (\Throwable $th) {  
            DB::rollback();
            DB::table('failed_jobs')->insert([ 
                'connection'=>'FirstJob',
                'queue'=>'',
                'payload'=>'', 
                'exception'=>$th->getMessage().' on line '.$th->getLine(),
                'failed_at'=>date("Y-m-d H:i:s")
            ]);
        } 
    }

}