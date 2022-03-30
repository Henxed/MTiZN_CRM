<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    protected $maxAttempts = 3; // default is 5
    protected $decayMinutes = 2; // default is 1
    // ...
}
