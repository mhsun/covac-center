<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('vaccination:send-reminders')->dailyAt('21:00');
