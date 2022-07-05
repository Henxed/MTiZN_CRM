<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Enterprises;
use App\Models\Safety;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as Req;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SafetyController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:safety.partners.create', ['only' => ['create','store']]);
        $this->middleware('permission:safety.partners.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:safety.partners.destroy', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
         // Поиск по ключевому слову. ?filter[search]=слово
        $globalSearch = AllowedFilter::callback('search', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")->orWhere('inn', 'LIKE', "%{$value}%");
            });
        });

        // Сортировка + поиск + страницы
        $enterprises = QueryBuilder::for(Safety::class)
            ->defaultSort('collective_agreement')
            ->allowedSorts(['name', 'inn', 'region', 'collective_agreement', 'sum_contractual', 'in_total', 'start_year', 'accidents_deadly', 'accidents_deadly_at', 'accidents_heavy', 'accidents_heavy_at', 'accidents_group', 'accidents_group_at'])
            ->allowedFilters([
                $globalSearch,
                AllowedFilter::scope('region'),
            ])
            ->select('enterprises.name', 'enterprises.inn','areas.region', 'safeties.*')
            ->leftJoin('enterprises', function($join) {
                $join->select('name', 'area_id', 'inn')->on('enterprises.id', '=', 'safeties.enterprise_id');
            })
            ->leftJoin('areas', function($join) {
                $join->select('region')->on('areas.id', '=', 'enterprises.area_id');
            })
            ->paginate()
            ->withQueryString();

        return Inertia::render('Safety/Partners/Index', [
            'safety_sum' => Safety::select(
                DB::raw('COUNT(`collective_agreement`) ca'),
                DB::raw('SUM(`sum_contractual`) sc'),
                DB::raw('(SELECT SUM(`accidents_group`) + SUM(`accidents_heavy`) + SUM(`accidents_deadly`) FROM `safeties` WHERE `deleted_at` IS NULL AND  (QUARTER(`accidents_group_at`) = QUARTER(NOW()) OR QUARTER(`accidents_heavy_at`) = QUARTER(NOW()) OR QUARTER(`accidents_deadly_at`) = QUARTER(NOW()))) qa'),
            )->first(),
            'regions' => Areas::select('id', 'region as label')->get(),
            'enterprises' => $enterprises,
            'queryBuilderProps' => [
                'sort'    => $request->query('sort'), //по какому полю сортируем
                'page'    => Paginator::resolveCurrentPage(), //текущая страница
                'filter' => [
                    'region'  => Arr::has($request->filter, 'region') ? $request->filter['region'] : null,
                    'search'  => Arr::has($request->filter, 'search') ? $request->filter['search'] : null,
                ],
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Safety/Partners/Create');
    }

    public function store(Request $request)
    {
        Req::validate([
            'enterprise_id' => ['required', Rule::unique('safeties')],
            'collective_agreement' => 'required',
            'sum_contractual' => 'required',
            'in_total' => 'required',
            'start_year' => 'required',
        ],[
            'enterprise_id.unique' => 'Данные с таким предприятием уже существуют, вы можете их отредактировать.'
        ]);

        Safety::create($request->all());

        $s = Safety::select('area_id', DB::raw('count(safeties.id) as s_count'))->leftJoin('enterprises', function($join) {
            $join->on('enterprises.id', 'enterprise_id');
        })->where('area_id', DB::raw("(SELECT area_id FROM enterprises WHERE id = ". $request->enterprise_id .")"))->groupBy('area_id')->first();
        Areas::where('id', $s->area_id)->update(['contract' => $s->s_count]);

        return Redirect::route('safety.partners.index')->with('success', 'Информация добавлена!');
    }

    public function edit(Safety $partner)
    {
        return Inertia::render('Safety/Partners/Edit', [
            'enterprise' => Enterprises::select(['id', 'name as label'])->where('id', $partner->enterprise_id)->first(),
            'partner' => $partner,
        ]);
    }

    public function update(Safety $partner)
    {
        Req::validate([
            'enterprise_id' => ['required', Rule::unique('safeties')->ignore($partner->id)],
            'collective_agreement' => 'required',
            'sum_contractual' => 'required',
            'in_total' => 'required',
            'start_year' => 'required',
        ],[
            'enterprise_id.unique' => 'Данные с таким предприятием уже существуют, вы можете их отредактировать.'
        ]);

        $partner->update(Req::all());

        $s = Safety::select('area_id', DB::raw('count(safeties.id) as s_count'))->leftJoin('enterprises', function($join) {
            $join->on('enterprises.id', 'enterprise_id');
        })->where('area_id', DB::raw("(SELECT area_id FROM enterprises WHERE id = ". $partner->enterprise_id .")"))->groupBy('area_id')->first();
        Areas::where('id', $s->area_id)->update(['contract' => $s->s_count]);

        return Redirect::route('safety.partners.index')->with('success', 'Информация обновлена!');
    }

    public function destroy(Safety $partner)
    {
        $partner->destroy($partner->id); //удаление

        return Redirect::route('safety.partners.index')->with('success', "Данные удалены!");
    }
}
