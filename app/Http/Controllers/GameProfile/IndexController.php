<?php

namespace App\Http\Controllers\GameProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $chat = null;

        return Inertia::render('GameProfile/Index', [
            'chat' => $chat
        ]);
    }
}
