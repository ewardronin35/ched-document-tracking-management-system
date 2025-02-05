<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Passwords\SendsPasswordResetEmails; // Correct namespace

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;
}
