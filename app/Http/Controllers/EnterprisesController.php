<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\AreasUser;
use App\Models\Enterprises;
use App\Models\Status;
use Illuminate\Support\Facades\Request as Req;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Inertia\Inertia;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EnterprisesController extends Controller
{

    public function show($id)
    {
        //
    }

    public function enterprises(Request $request, $id)
    {
        // Поиск по ключевому слову. ?filter[search]=слово
        $globalSearch = AllowedFilter::callback('search', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")->orWhere('inn', 'LIKE', "%{$value}%");
            });
        });

        // Сортировка + поиск + страницы
        $enterprises = QueryBuilder::for(Enterprises::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'okvd_name', 'ane', 'inn', 'status_id', 'updated_at'])
            ->allowedFilters(['name', 'okvd_name', 'ane', 'inn', 'status_id', 'updated_at', $globalSearch])
            ->with('status')
            ->where('area_id', $id)
            ->whereNull('enterprises_id')
            ->paginate()
            ->withQueryString();


        return Inertia::render('Maps/Enterprises/Index', [
            'region' => Areas::select(['id', 'region'])->findOrFail($id),
            'enterprises' => $enterprises,
            'queryBuilderProps' => [
                'sort'    => $request->query('sort'), //по какому полю сортируем
                'page'    => Paginator::resolveCurrentPage(), //текущая страница
                'filter' => ['search'  => $request->query('search')],
            ]
        ]);
    }

    public function create($id)
    {
        $data = Areas::select(['id', 'region'])->findOrfail($id);

        if(Auth::user()->checkArea($data->id)){
            return abort(403);
        }
        return Inertia::render('Maps/Enterprises/Create', [
            'region' => $data,
            'statuses' => Status::where('model', 'enterprises')->where('active', true)->get(),
        ]);

    }

    public function store(Request $request)
    {
        Req::validate([
            'name' => 'required',
            'inn' => 'required',
            'okvd' => 'required',
            'okvd_name' => 'required',
            'status_id' => 'required',
            'rns' => 'required',
        ]);

        if(Auth::user()->checkArea($request->area_id)){
            return abort(403);
        }

        Enterprises::create($request->all());

        return Redirect::route('regions.enterprises', Req::get('area_id'))->with('success', 'Предприятие добавлено!');
    }

    public function edit($id)
    {
        $data = Enterprises::findOrfail($id);

        if(Auth::user()->checkArea($data->area_id)){
            return abort(403);
        }
        return Inertia::render('Maps/Enterprises/Edit', [
            'enterprises' => $data,
            'statuses' => Status::where('model', 'enterprises')->where('active', true)->get(),
        ]);
    }


    public function update(Enterprises $enterprises, $id)
    {
        Req::validate([
            'name' => 'required',
            'inn' => 'required',
            'okvd' => 'required',
            'okvd_name' => 'required',
            'status_id' => 'required',
            'rns' => 'required',
        ]);

        if(Auth::user()->checkArea(Req::get('area_id'))){
            return abort(403);
        }

        $enterprises->where('id', $id)->update(Req::all());

        return Redirect::route('regions.enterprises', Req::get('area_id'))->with('success', 'Предприятие актулизированно!');
    }


    public function destroy(Enterprises $enterprises, $id)
    {

        $data = Enterprises::findOrfail($id);

        if(Auth::user()->checkArea($data->area_id)){
            return abort(403);
        }

        $data->destroy((int)$id); //удаление

        return Redirect::route('regions.enterprises', $data->area_id)->with('success', "Предприятие удалено!");
    }

    // Загрузка данных с excel файла
    public function data_upload(Request $request)
    {


        if($request->hasFile('csv')) {

            $f = fopen($request->file('csv'), "rt") or die("Ошибка!");

            for ($i=0; ($data=fgetcsv($f,1000,";"))!==false; $i++) {

                if((int)$data[0]){


                    $d = Enterprises::updateOrCreate(
                        ['inn' => $data[3], 'area_id' => $data[0]],
                        [
                            'name' => $data[1],
                            'rns' => $data[2],
                            'status_id' => $data[4],
                            'okvd' => $data[5],
                            'okvd_name' => $data[6],
                            'ane' => $data[7],
                            'nde' => $data[8],
                            'factors' => $data[9],
                            'total_jobs' => $data[10],
                            'workplaces_respect' => $data[11],
                            'workplaces_three' => $data[12],
                            'workplaces_four' => $data[13],
                            'total_factors' => $data[14],
                            'start_year_factors' => $data[15],
                            'address' => $data[16],
                        ]
                    );
                }


            }
            fclose($f);

        }

        //return Redirect::route('csv')->with('info', "Обработал");
    }
}
