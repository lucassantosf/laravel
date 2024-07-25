<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('post:update')->everyMinute()->appendOutputTo(storage_path('logs/posts.log'));