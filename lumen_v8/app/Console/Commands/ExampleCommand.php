<?php

namespace App\Console\Commands;

use Illuminate\Console\Command; 
use Carbon\Carbon; 
use DB;

class ExampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:example';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command example lumen';

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
     * @return mixed
     */
    public function handle() { 
        $this->info('------------------------------------------------------------------------------------');
        $this->info(Carbon::now());
        $this->info('Command example');
 
        try{
            DB::beginTransaction();
 
            $model = true;  

            if($model){
                // do something... 
            }

            DB::commit();             
        } catch (\Throwable $th) {
            DB::rollback();            
            $this->info('Error: '.$th->getMessage());
        }   
         
        $this->info('Fim');
    }    
}