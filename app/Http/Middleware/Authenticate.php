<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $current_uri = request()->segments(); 
    //  dd($current_uri);
      if (! $request->expectsJson()) {
          
          if($current_uri[0] == "Admin"){
          
            return route('admin_login');
          }
         
      }
    }
}
