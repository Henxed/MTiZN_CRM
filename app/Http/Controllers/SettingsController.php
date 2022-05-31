<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use App\Models\Enterprises;

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
    public function uploadEntrLoad (Request $request){
        $request->validate([
            'csv' => 'required',
        ],[
            'csv.required' => 'Вы не добавили файл!'
        ]);

        if($request->hasFile('csv')) {
            $error = [];
            $f = fopen($request->file('csv'), "rt") or die("Ошибка!");
            for ($i=0; ($data=fgetcsv($f,35000,";"))!==false; $i++) {

                if((int)$data[0]){


                    $entr = Enterprises::where('area_id', $data[0])->where('inn', $data[3])->first();
                    if($entr){
                        if(str_replace(' ', '', str_replace(',', '.',  $data[4]))) {

                            $entr->employed_public = str_replace(' ', '', str_replace(',', '.',  $data[4]));
                        }
                        if(str_replace(' ', '', str_replace(',', '.',  $data[5]))) {
                            $entr->employed_temporary = str_replace(' ', '', str_replace(',', '.',  $data[5]));
                        }
                        $entr->save();
                    }else{
                        $a = Areas::select('region')->where('id', $data[0])->first();
                        array_push($error, [ 'inn' => $data[3], 'region' => $a->region]);
                    }
//echo $data[0] . " - " . $data[2] . " - " . $data[3] . " - " .$data[4] . " - " . $data[5]. " <br> ";

                }
            }

            fclose($f);
            return Redirect::route('entr-load')->with(['success' => 'Данные районов массово обновлены!', 'error' => $error]);
        }
    }
}
