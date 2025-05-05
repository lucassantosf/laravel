<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('post:update')->everyMinute()->appendOutputTo(storage_path('logs/posts.log'));
