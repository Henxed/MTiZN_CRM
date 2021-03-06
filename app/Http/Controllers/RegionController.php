<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Enterprises;
use App\Models\AreasExtra;
use App\Models\AreasLog;
use App\Models\AreasUser;
use App\Models\Safety;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use Auth;

class RegionController extends Controller
{

    // Сводная информация про все районы
    public function index()
    {
        return Inertia::render('Maps/Index', [
            'lvl_all' => Setting::where('key', 'lvl_all')->first(),
            'regions' => Areas::orderBy('region')->get(),
            'regions_sum' => Areas::whereNull('areas_id')->count(),
            'regions_sum_b' => Areas::whereNull('areas_id')->sum('lvl'),
            'enterprises_count' => Enterprises::count(),
            'access_region' => AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id'),
            'entr_sum' => Enterprises::select(
                DB::raw('SUM(`work_part`) as wp'),
                DB::raw('SUM(`idle`) as idle'),
                DB::raw('SUM(`vacations`) as v'),
                DB::raw('SUM(`dismissed`) as d'),
                DB::raw('SUM(`remote`) as r'),
                DB::raw('SUM(`sum_arrears`) as sa'),
            )->first(),
            'safety_sum' => Safety::select(
                DB::raw('COUNT(`collective_agreement`) ca'),
                DB::raw('(SELECT SUM(`accidents_group`) + SUM(`accidents_heavy`) + SUM(`accidents_deadly`) FROM `safeties` WHERE `deleted_at` IS NULL AND  (QUARTER(`accidents_group_at`) = QUARTER(NOW()) OR QUARTER(`accidents_heavy_at`) = QUARTER(NOW()) OR QUARTER(`accidents_deadly_at`) = QUARTER(NOW()))) qa'),
            )->first()
        ]);
    }

    // Карта всех районов
    public function map()
    {

        return Inertia::render('Maps/Regions', [
            'lvl_all' => Setting::where('key', 'lvl_all')->first(),
            'regions' => Areas::with('distance')->whereNotNull('d')->get(),
            'vacancy' => Areas::whereNull('areas_id')->sum('vacancy'),
            'access_region' => AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id'),
        ]);
    }

    // одна запись про район, можно дополнить информаций
    public function show($id)
    {

        return Inertia::render('Maps/Region', [
            'regions' => Areas::orderBy('region')->get(),
            'region' => Areas::with('extra', 'selsoviet', 'areas_children')->findOrFail($id),
            'access_region' => AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id'),
            'entr_sum' => Enterprises::select(
                DB::raw('SUM(`work_part`) as wp'),
                DB::raw('SUM(`idle`) as idle'),
                DB::raw('SUM(`vacations`) as v'),
                DB::raw('SUM(`dismissed`) as d'),
                DB::raw('SUM(`remote`) as r'),
                DB::raw('SUM(`sum_arrears`) as sa'),
            )->where('area_id', $id)->get()->first()->toArray() //DB::select('SELECT SUM(`work_part`) as wp, SUM(`idle`) as idle, SUM(`vacations`) as v, SUM(`dismissed`) as d, SUM(`remote`) as r FROM `enterprises` WHERE `area_id` = ?', [$id])[0] ?? 0,
        ]);
    }

    // редактирование района
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
        $request->validate([
            'csv' => 'required',
        ],[
            'csv.required' => 'Вы не добавили файл!'
        ]);

        if($request->hasFile('csv')) {

            $f = fopen($request->file('csv'), "rt") or die("Ошибка!");
            for ($i=0; ($data=fgetcsv($f,35000,";"))!==false; $i++) {

                if((int)$data[0]){
                    //echo $data[0] . " - " . $data[2] . " - " . $data[3] . " - " .$data[4] . " - " . $data[5]. " <br> ";
                    $region = Areas::find($data[0]);
                    if($region){
                        $region->unemployed = str_replace(' ', '', str_replace(',', '.', $data[2])) ?? 0; // количество безработных
                        $region->lvl = str_replace(' ', '', str_replace(',', '.', $data[3])) ?? 0; // уровень безработных
                        $region->vacancy = str_replace(' ', '', str_replace(',', '.', $data[4])) ?? 0; // вакансий
                        $region->tension = str_replace(' ', '', str_replace(',', '.', $data[5])) ?? 0; // напряженность / Безработные к вакансиям, %
                        $region->save();
                    }else{
                        return Redirect::back()->with('error', 'Такого района нет или другая ошибка!');
                    }
                }
            }

            fclose($f);
            return Redirect::route('stats-load')->with('success', 'Данные районов массово обновлены!');
        }


    }
}
