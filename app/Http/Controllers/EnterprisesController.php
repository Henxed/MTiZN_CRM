<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\AreasUser;
use App\Models\Department;
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
use Illuminate\Support\Arr;

class EnterprisesController extends Controller
{

    public function show($id)
    {
        $data = Enterprises::with('status')->findOrfail($id);

        return Inertia::render('Maps/Enterprises/Show', [
            'region' => Areas::select(['id', 'region'])->findOrFail($data->area_id),
            'enterprise' => $data,
            'access_region' => AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id'),
        ]);
    }

    public function enterprises(Request $request, $id)
    {
        // Поиск по ключевому слову. ?filter[search]=слово
        $globalSearch = AllowedFilter::callback('search', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")->orWhere('inn', 'LIKE', "%{$value}%");
            });
        });
        $min_amy = AllowedFilter::callback('min_amy', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('amy', '>=', ($value));
            });
        });
        $max_amy = AllowedFilter::callback('max_amy', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('amy', '<=', ($value));
            });
        });
        $default = ['okvd_name', 'inn', 'status_id'];

        // Нужно понять, что ты в отделе и вытаскивать поля отдела
        $dep = Department::select('entr_filter')->leftJoin('department_user', function($join){
            $join->on('department_id', 'departments.id');
        })->where('user_id', Auth::user()->id)->first();
        if($dep){
            $dep = json_decode($dep->toArray()['entr_filter']);
            $dep = count($dep) ? $dep : null;
        }

        $sort = collect(($dep ?? $default))->prepend('name')->push('updated_at');
        $filter = collect($sort)->push($globalSearch, $min_amy, $max_amy);

        // Сортировка + поиск + страницы
        $enterprises = QueryBuilder::for(Enterprises::class)
            ->defaultSort('name')
            ->allowedSorts($sort->toArray())
            ->allowedFilters($filter->toArray())
            ->with('status')
            ->where('area_id', $id)
            ->whereNull('enterprises_id')
            ->paginate()
            ->withQueryString();

        return Inertia::render('Maps/Enterprises/Index', [
            'region' => Areas::select(['id', 'region'])->findOrFail($id),
            'region_count' => Enterprises::where('area_id', $id)->count(),
            'enterprises' => $enterprises,
            'access_region' => AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id'),
            'table' => $sort,
            'queryBuilderProps' => [
                'sort'    => $request->query('sort'), //по какому полю сортируем
                'page'    => Paginator::resolveCurrentPage(), //текущая страница
                'filter' => [
                    'search'  => Arr::has($request->filter, 'search') ? $request->filter['search'] : null,
                    'min_amy' => Arr::has($request->filter, 'min_amy') ? $request->filter['min_amy'] : null,
                    'max_amy' => Arr::has($request->filter, 'max_amy') ? $request->filter['max_amy'] : null
                ],
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

    public function edit(Enterprises $enterprise)
    {
        //$data = Enterprises::findOrfail($id);

        if(Auth::user()->checkArea($enterprise->area_id)){
            return abort(403);
        }
        return Inertia::render('Maps/Enterprises/Edit', [
            'enterprises' => $enterprise,
            'statuses' => Status::where('model', 'enterprises')->where('active', true)->get(),
        ]);
    }


    public function update(Enterprises $enterprise)
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

        $enterprise->update(Req::all());

        return Redirect::route('regions.enterprises', Req::get('area_id'))->with('success', 'Предприятие актулизированно!');
    }


    public function destroy(Enterprises $enterprise)
    {

        if(Auth::user()->checkArea($enterprise->area_id)){
            return abort(403);
        }

        $enterprise->destroy($enterprise->id); //удаление

        return Redirect::route('regions.enterprises', $enterprise->area_id)->with('success', "Предприятие удалено!");
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
