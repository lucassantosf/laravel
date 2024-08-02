<?php

namespace App\Jobs;

use App\Models\Post; 
use DB;

class FirstJob extends Job
{
    protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
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
            
            $this->post->update(['status'=>!$this->post->status]);

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