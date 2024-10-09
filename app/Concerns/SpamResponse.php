<?php

namespace App\Concerns;

use Closure;
use Illuminate\Http\Request;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class SpamResponse implements SpamResponder
{
    public function respond(Request $request, Closure $next)
    {
        return redirect()->back();
    }
}
