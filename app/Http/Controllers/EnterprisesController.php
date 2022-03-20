<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\AreasUser;
use App\Models\Enterprises;
use Illuminate\Support\Facades\Request as Req;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Inertia\Inertia;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

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
            ->allowedSorts(['name', 'amy', 'cw', 'inn', 'updated_at'])
            ->allowedFilters(['name', 'amy', 'cw', 'inn', 'updated_at', $globalSearch])
            ->where('area_id', $id)
            ->whereNull('enterprises_id')
            ->paginate()
            ->withQueryString();


        return Inertia::render('Maps/Enterprises/Index', [
            'region' => Areas::with('extra', 'selsoviet', 'areas_children')->findOrFail($id),
            'enterprises' => $enterprises,
            'queryBuilderProps' => [
                'sort'    => $request->query('sort'), //по какому полю сортируем
                'page'    => Paginator::resolveCurrentPage(), //текущая страница
                'filter' => ['search'  => $request->query('search')],
            ]
        ]);
    }

    public function create()
    {


    }

    public function store(Request $request)
    {

    }

    public function edit(Request $request, $id)
    {
        $data = Enterprises::findOrfail($id);

        if(Auth::user()->checkArea($data->area_id)){
            return abort(403);
        }
        return Inertia::render('Maps/Enterprises/Edit', [
            'enterprises' => $data,
        ]);
    }


    public function update(Enterprises $enterprises, $id)
    {
        Req::validate([
            'name' => 'required',
            'inn' => 'required'
        ]);

        if(Auth::user()->checkArea(Req::get('area_id'))){
            return abort(403);
        }

        $enterprises->where('id', $id)->update(Req::only('name','cw','amy','inn'));

        return Redirect::route('regions.enterprises', Req::get('area_id'))->with('success', 'Предприятие актулизированно!');
    }


    public function destroy(Enterprises $enterprises, $id)
    {

        $data = Enterprises::findOrfail($id);

        if(Auth::user()->checkArea($data->area_id)){
            return abort(403);
        }

        $data->destroy((int)$id); //удаление

        return response()->json("Предприятие удалено!");
    }
}
