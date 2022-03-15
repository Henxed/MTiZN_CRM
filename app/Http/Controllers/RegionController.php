<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Enterprises;
use App\Models\AreasExtra;
use App\Models\AreasUser;
use Illuminate\Http\Request;
use Inertia\Inertia;


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
        ]);
    }

    public function region_api_ajax($id)
    {
        $region = Areas::with('selsoviet', 'areas_children')->find($id);

        //$enterprises = Enterprises::where('area_id', $id)->whereNull('enterprises_id')->with('children')->get();

        return view('region.region', compact('region'));
    }


    public function region_edit(Request $request, $id, $type)
    {
        if(!Auth::user()->checkArea($id)){
            abort(403);
        }

        $region = Areas::with('extra', 'areas_children')->findOrfail($id);
        $enterprises = Enterprises::where('area_id', $id)->whereNull('enterprises_id')->orderBy('name', 'asc')->paginate(25);
        //$tpl = Tpl::get();
        //dd(json_decode($tpl[0]['content']));
        return view('region.edit', compact('region', 'enterprises', 'type'));
    }

    public function region_edit_extra_api(Request $request, $id)
    {

        if(!Auth::user()->checkArea($id)){
            abort(403);
        }

        $AE = AreasExtra::where('area_id', $id)->first();
        if(!$AE){
          $AE = new AreasExtra();
        }
        $AE->area_id = $id;
        $input = $request->all();
        $AE->fill($input)->save();

        return response()->json([
            "message" => "Данные обновлены!",
            "errors" => []
        ], 200);
    }


    public function region_update(Request $request, $id)
    {
        if(!Auth::user()->checkArea()){
            abort(403);
        }

        $region = Areas::findOrfail($id);
        $request->validate([
            'population' => 'required',
            'region' => 'required',
            'city' => 'required',
        ]);

        $input = $request->all();
        $region->fill($input)->save();

        return response()->json([
            "message" => "Данные обновлены!",
            "errors" => []
        ], 200);
    }

    public function region_extra_update(Request $request, $id)
    {
        if(!Auth::user()->checkArea($id)){
            abort(403);
        }

        $region = Areas::findOrfail($id);
        $input = $request->all();
        $region->fill($input)->save();

        return response()->json([
            "message" => "Данные обновлены!",
            "errors" => []
        ], 200);
    }


    // Загрузка данных с excel файла
    public function region_data()
    {
        //$regions = Areas::with('areas_children')->orderBy('region')->get();

        return view('cp.region_data');
    }

    // Загрузка данных с excel файла
    public function region_data_upload(Request $request)
    {

        if($request->hasFile('csv')) {

            $f = fopen($request->file('csv'), "rt") or die("Ошибка!");
            for ($i=0; ($data=fgetcsv($f,1000,";"))!==false; $i++) {

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
