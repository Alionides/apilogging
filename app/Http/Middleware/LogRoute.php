<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (app()->environment('local')) {
            $log = [
                'method' => $request->getMethod(),
                'body' => $request->all(),
                'ip' => $request->ip(),
                'url' => $request->getUri(),
                'path'=>'',
                'id'=>'3',        //Auth::user()->id;
                'username'=>'test-api-username', //Auth::user()->username;
                'email'=>'test@test.email',   //Auth::user()->email;
                'response' => $response->getContent(),
            ];
            $routename = explode("/", $request->getUri())[3];
            // config(['logging.channels.single.path' => storage_path('logs/api-logging/' .date("Y-m"). '/'. date("Y-m-d") .'/'.$request->getMethod().'__'.$routename.'.json')]);
            // Log::channel('single')->info(json_encode($log));

            Storage::disk('logs')->put($request->getMethod().'__'.$routename.'.json', response()->json($log));
        }

        return $response;

        //return $next($request);
    }
}
