<?php

namespace App\Exceptions;
use Throwable;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    // public function register()
    // {
    //     if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
           
    //        // return redirect()->route('admin_login')->with('message', 'Your session has expired. Please log in again.');
    //     }
        
    //     // if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
    //     //     return redirect()->route('admin_login')->with('message', 'Page not found. Please log in again.');
    //     // }
    // }
    public function render($request, Throwable $exception)
{      
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
           
           $guard = $exception->guards()[0]; // Get the guard from the exception
          
           if ($guard === 'therapist') {
             return redirect()->route('therapist_login_view')->with('message', 'Your session has expired. Please log in again.');
           } elseif ($guard === 'web') {
            return redirect()->route('admin_login')->with('message', 'Your session has expired. Please log in again.');
           }
         
        }

     return parent::render($request, $exception);
}
}
