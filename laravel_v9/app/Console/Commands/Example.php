<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Example extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exemple:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Example command';

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
    public function handle() 
    {
        $this->info('------------------------------------------------------------------------------------');
        $this->info(Carbon::now());
        $this->info('It is the end');
        $this->info('------------------------------------------------------------------------------------');
    }     
    
}