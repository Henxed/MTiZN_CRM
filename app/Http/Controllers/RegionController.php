<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Enterprises;
use App\Models\AreasExtra;
use App\Models\AreasLog;
use App\Models\AreasUser;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

use Auth;

class RegionController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return Inertia::render('Maps/Regions', [
            'regions' => Areas::with('distance')->whereNotNull('d')->get(),
            'access_region' => AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id'),
        ]);
    }

    // одна запись про район, можно дополнить информаций
    public function show($id)
    {

        return Inertia::render('Maps/Region', [
            'regions' => Areas::get(),
            'region' => Areas::with('extra', 'selsoviet', 'areas_children')->findOrFail($id),
            'access_region' => AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id'),
        ]);
    }


    public function edit(Request $request, $id)
    {

        if(Auth::user()->checkArea($id)){
            abort(403);
        }

        return Inertia::render('Maps/Edit', [
            'region' => Areas::with('extra')->findOrFail($id),
            'log' => AreasLog::where('area_id', $id)->with('user')->orderBy('created_at', 'desc')->get()
        ]);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'population' => 'required',
            'region' => 'required',
            'city' => 'required',
            'tension' => 'required',
            'lvl' => 'required',
            'in_employment' => 'required',
        ]);

        if(Auth::user()->checkArea($id)){
            abort(403);
        }

        AreasLog::setLog(Auth::user()->id, $id, $request->all(), 'USER_LOG');
        Areas::where('id', $id)->update($request->except(['school', 'vvuz', 'ssuz', 'detdom', 'nou', 'ur', 'jobs']));
        AreasExtra::where('area_id', $id)->update($request->only(['school', 'vvuz', 'ssuz', 'detdom', 'nou', 'ur', 'jobs']));

        return Redirect::back()->with('success', 'Данные региона обновлены!');
    }


    // Загрузка данных с excel файла
    public function region_data_upload(Request $request)
    {

        if($request->hasFile('csv')) {

            $f = fopen($request->file('csv'), "rt") or die("Ошибка!");
            for ($i=0; ($data=fgetcsv($f,35000,";"))!==false; $i++) {

                if((int)$data[0]){
                    //echo $data[0] . " - " . $data[2] . " - " . $data[3] . " - " .$data[4] . " - " . $data[5]. " <br> ";
                    $region = Areas::findOrfail($data[0]);
                    $region->unemployed = $data[2]; // количество безработных
                    $region->lvl = $data[3]; // уровень безработных
                    $region->vacancy = $data[4]; // вакансий
                    $region->tension = $data[5]; // напряженность / Безработные к вакансиям, %
                    $region->save();
                }


            }
            fclose($f);

        }

        return view('cp.region_data');
    }
}
