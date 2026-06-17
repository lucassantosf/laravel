<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $resource = Post::first();

            if ($resource) {
                $resource->update(['status' => !$resource->status]);
            }

            DB::commit();

            return 0;
        } catch (\Throwable $th) {
            DB::rollback();

            $this->error('Error updating posts: ' . $th->getMessage());

            return 1;
        }
    }
}
