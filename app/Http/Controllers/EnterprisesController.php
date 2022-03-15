<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Enterprises;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Inertia\Inertia;
use Illuminate\Pagination\Paginator;

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
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        if($request->ajax()) {
            $enterprises = Enterprises::findOrFail($id);

            return response()->json([
                "inputs" => [
                    'name' => $enterprises->name,
                    'amy' => $enterprises->amy,
                    'cw' => $enterprises->cw,
                    'inn' => $enterprises->inn
                ],
                "labels" =>[
                    'name' => 'Название',
                    'amy' => 'Средняя ЗП',
                    'cw' => 'Работников',
                    'inn' => 'ИНН'
                ],
                'title' => 'Редактирование предприятия',
                "errors" => []
            ], 200);;
        }
    }


    public function update(Request $request, $id)
    {
        if($request->ajax()) {


            $request->validate([
                'name' => 'required',
                'inn' => 'required'
            ]);
            $enterprises = Enterprises::findOrFail($id);

            // $region = Areas::findOrfail($enterprises->area_id);
            // if(!PermissionsCheck::hasRole(['admin', 'moderator'])){
            //     if(Auth::user()->areas->where('id',$id)->count() == 0){
            //         if(!$region->areas_id){
            //             abort(403);
            //         }
            //     }
            // }

            $enterprises->fill($request->all())->save();

            return response()->json([
                'id' => $enterprises->id,
                'name' => $enterprises->name,
                'amy' => $enterprises->amy,
                'cw' => $enterprises->cw,
                'inn' => $enterprises->inn,
                'time' =>  \Carbon\Carbon::parse($enterprises->updated_at)->toDateTimeString(),
                "message" => "Данные обновлены!",
                "errors" => []
            ], 200);;
        }
    }


    public function destroy(Enterprises $enterprises, $id)
    {
      Enterprises::destroy((int)$id); //удаление

      return response()->json("Удалил!");
    }
}
