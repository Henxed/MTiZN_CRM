<?php

namespace App\Http\Controllers;

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

class SafetyController extends Controller
{
    public function index(Request $request): Object
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
            ->allowedSorts(['name', 'inn', 'region', 'collective_agreement'])
            ->allowedFilters($globalSearch)
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
            'enterprises' => $enterprises,
            'queryBuilderProps' => [
                'sort'    => $request->query('sort'), //по какому полю сортируем
                'page'    => Paginator::resolveCurrentPage(), //текущая страница
                'filter' => [
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
            'enterprise_id' => 'required',
            'collective_agreement' => 'required',
            'sum_contractual' => 'required',
        ]);

        Safety::create($request->all());

        return Redirect::route('safety.partners.index')->with('success', 'Информация добавлена!');
    }

    public function edit(Safety $partner)
    {
        return Inertia::render('Safety/Partners/Edit', [
            'enterprise' => Enterprises::select(['id', 'name as label'])->where('id', $partner->enterprise_id)->get(),
            'partner' => $partner,
        ]);
    }
}
