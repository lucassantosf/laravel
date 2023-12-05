<?php

namespace App\Console\Commands;

use App\Models\Post;
use DB;
use Illuminate\Console\Command;

class CommandExample extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a command example, it updates posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction(); 

        try {

            $resource = Post::where('status',0)->first();

            $resource->update(['status'=>1]);

            DB::commit();             
        } catch (\Throwable $th) {
            DB::rollback();            
        }
    }
    
}