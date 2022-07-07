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
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Exports\EnterprisesExport;
use App\Models\Safety;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EnterprisesController extends Controller
{

    public function show($id, $enterprises)
    {
        $data = Enterprises::with('status')->findOrfail($enterprises);

        return Inertia::render('Maps/Enterprises/Show', [
            'region' => Areas::select(['id', 'region'])->findOrFail($data->area_id),
            'enterprise' => $data,
            'access_region' => AreasUser::where('user_id', Auth::user()->id)->pluck('areas_id'),
        ]);
    }

    public function index(Request $request, $id)
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
            'enterprises_count' => Enterprises::count(),
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

    public function all(Request $request)
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

        $e = Schema::getColumnListing('enterprises');
        $dep = ['region'];
        $i=1;
        foreach ($e as $key => $value) {
            if($value !== 'cw' && $value !== 'workplaces_three' && $value !== 'workplaces_four' && $value !== 'total_factors' && $value !== 'start_year_factors' && strpos(__('inputs.ent.'.$value), 'inputs.ent') === false){
                $dep[$i] = $value;
                $i++;
            }
        }

        $sort = collect(($dep ?? $default));
        $filter = collect($sort)->push($globalSearch, $min_amy, $max_amy);

        // Сортировка + поиск + страницы
        $enterprises = QueryBuilder::for(Enterprises::class)
            ->defaultSort('name')
            ->allowedSorts($sort->toArray())
            ->allowedFilters($filter->toArray())
            ->with('status')
            ->select( 'enterprises.*', 'areas.region')
            ->leftJoin('areas', function($join) {
                $join->select('region')->on('areas.id', '=', 'enterprises.area_id');
            })
            ->whereNull('enterprises_id')
            ->paginate()
            ->withQueryString();

        return Inertia::render('Maps/Enterprises/All', [
            'enterprises_count' => Enterprises::count(),
            'enterprises' => $enterprises,
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
            'inn' => 'required|min:6',
            'okvd' => 'required',
            'okvd_name' => 'required',
            'status_id' => 'required',
        ]);

        if(Auth::user()->checkArea($request->area_id)){
            return abort(403);
        }

        $enterprise = Enterprises::updateOrCreate(['inn' => $request->inn, 'area_id' => $request->area_id], $request->all());

        if($request->partner['collective_agreement'] && Auth::user()->can('safety.partners.create')){
            $validator = Validator::make($request->partner, [
                'collective_agreement' => 'required',
                'sum_contractual' => 'required',
                'in_total' => 'required',
                'start_year' => 'required',
            ]);
            $validator->validated();

            Safety::updateOrCreate(['enterprise_id' => $enterprise->id], Req::all()['partner']);
        }

        Areas::where('id', $request->area_id)->update(['subject' => Enterprises::where('area_id', $request->area_id)->count()]);

        return Redirect::route('regions.enterprises.index', Req::get('area_id'))->with('success', 'Предприятие добавлено!');
    }

    public function edit($id, Enterprises $enterprise)
    {
        //$data = Enterprises::findOrfail($id);

        if(Auth::user()->checkArea($enterprise->area_id)){
            return abort(403);
        }
        return Inertia::render('Maps/Enterprises/Edit', [
            'region' => Areas::select(['id', 'region'])->findOrfail($id),
            'enterprises' => $enterprise,
            'partner' => Safety::where('enterprise_id', $enterprise->id)->first(),
            'statuses' => Status::where('model', 'enterprises')->where('active', true)->get(),
        ]);
    }


    public function update($id, Enterprises $enterprise)
    {
        Req::validate([
            'name' => 'required',
            'inn' => 'required|min:6',
            'okvd' => 'required',
            'okvd_name' => 'required',
            'status_id' => 'required',
        ]);

        if(Auth::user()->checkArea(Req::get('area_id'))){
            return abort(403);
        }

        $enterprise->update(Req::all());

        if(Req::get('partner')['collective_agreement'] && Auth::user()->can('safety.partners.edit')){
            $validator = Validator::make(Req::get('partner'), [
                'collective_agreement' => 'required',
                'sum_contractual' => 'required',
                'in_total' => 'required',
                'start_year' => 'required',
            ]);
            $validator->validated();

            Safety::updateOrCreate(['enterprise_id' => $enterprise->id], Req::all()['partner']);
        }

        Areas::where('id', $enterprise->area_id)->update(['subject' => Enterprises::where('area_id', $enterprise->area_id)->count()]);

        return Redirect::route('regions.enterprises.index', Req::get('area_id'))->with('success', 'Предприятие актулизированно!');
    }


    public function destroy($id, Enterprises $enterprise)
    {

        if(Auth::user()->checkArea($enterprise->area_id)){
            return abort(403);
        }

        $enterprise->destroy($enterprise->id); //удаление

        return Redirect::route('regions.enterprises.index', $enterprise->area_id)->with('success', "Предприятие удалено!");
    }

    public function apiAll()
    {
        $search = AllowedFilter::callback('search', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")->orWhere('inn', 'LIKE', "%{$value}%");
            });
        });

        $data = QueryBuilder::for(Enterprises::class)
            ->select('id', 'name as label')
            ->defaultSort('name')
            ->allowedFilters($search)
            ->paginate(30)->withQueryString();

        return $data;
    }

    public function analytics($id = null)
    {

        // (COUNT(*) as mounth / (SELECT COUNT(*) FROM `enterprises`) as all * 100) as percent

        $data = Enterprises::select(
                'region',
                DB::raw("count(enterprises.id) as e_all, COUNT( NULLIF( employed_public, '' )) null_ep, COUNT( NULLIF( employed_temporary, '' ) ) null_et"),
                DB::raw("(SELECT COUNT(*) FROM `enterprises` e WHERE `updated_at` >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND area_id = enterprises.area_id) as mounth"),
                DB::raw("(SELECT COUNT(*) FROM `enterprises` e WHERE inn IS NULL AND area_id = enterprises.area_id) as no_inn")
            )->leftJoin('areas', function($join) {
                $join->select('region')->on('areas.id', 'enterprises.area_id');
            })->where(function ($query) use($id) {
                if($id) $query->where('area_id', $id);
            })->groupBy('region', 'enterprises.area_id')->get();
        return $data;
    }

    //выгрузка предприятий
    public function export()
    {
        return Excel::download(new EnterprisesExport, 'предприятия.xlsx');
    }

    // Загрузка данных с excel файла
    public function data_upload(Request $request)
    {


        if($request->hasFile('csv')) {

            $f = fopen($request->file('csv'), "rt") or die("Ошибка!");

            for ($i=0; ($data=fgetcsv($f,1000,";"))!==false; $i++) {

                if((int)$data[0]){

                        $d = Enterprises::updateOrCreate(
                            ['rns' => $data[2], 'area_id' => $data[0], ],
                            [
                                'name' => $data[1],
                                'inn' => $data[3],
                                //'status_id' => $data[4],
                                'okvd' => $data[5],
                                'okvd_name' => $data[6],
                                'ane' => str_replace(' ', '', str_replace(',', '.', $data[7])),
                                'nde' => str_replace(' ', '', str_replace(',', '.', $data[8])),
                                'factors' => str_replace(' ', '', str_replace(',', '.', $data[9])),
                                'total_jobs' => str_replace(' ', '', str_replace(',', '.', $data[10])),
                                'workplaces_respect' => str_replace(' ', '', str_replace(',', '.', $data[11])),
                                'workplaces_three' => str_replace(' ', '', str_replace(',', '.', $data[12])),
                                'workplaces_four' => str_replace(' ', '', str_replace(',', '.', $data[13])),
                                'total_factors' => str_replace(' ', '', str_replace(',', '.', $data[14])),
                                'start_year_factors' => str_replace(' ', '', str_replace(',', '.', $data[15])),
                                'address' => $data[16],
                                'sum_arrears' => str_replace(' ', '', str_replace(',', '.', $data[17])),
                                'employed_public' => str_replace(' ', '', str_replace(',', '.', $data[18])),
                                'employed_temporary' => str_replace(' ', '', str_replace(',', '.', $data[19])),
                                'work_part' => str_replace(' ', '', str_replace(',', '.', $data[20])),
                                'idle' => str_replace(' ', '', str_replace(',', '.', $data[21])),
                                'vacations' => str_replace(' ', '', str_replace(',', '.', $data[22])),
                                'dismissed' => str_replace(' ', '', str_replace(',', '.', $data[23])),
                                'remote' => str_replace(' ', '', str_replace(',', '.', $data[24]))
                            ]
                        );

                }


            }
            fclose($f);

        }

        //return Redirect::route('csv')->with('info', "Обработал");
    }
}
