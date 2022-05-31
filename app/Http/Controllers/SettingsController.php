<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function statsLoad(){
        return Inertia::render('Settings/StatsLoad', [
            'lvl_all' => Setting::where('key', 'lvl_all')->first(),
        ]);
    }

    public function setLvl(Request $r){
        Setting::where('key', 'lvl_all')->update(['value' => $r->lvl_all]);
    }

    public function entrLoad (){
        return Inertia::render('Settings/EntrLoad');
    }
}
