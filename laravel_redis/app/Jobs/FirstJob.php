<?php

namespace App\Jobs;

use DB;

class FirstJob extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
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
            DB::table('posts')->insert([ 
                'user_id'=>$this->request['user_id'],
                'title'=>$this->request['title'],
                'content'=>$this->request['content'],
                'status'=>1,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
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