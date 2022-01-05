<?php

namespace App\Http\Controllers;

use App\Model\Stat;
use Illuminate\Http\Request;

class StatController extends Controller
{
    function get_stat($id) {
        $stat = Stat::find($id);

        $arr = array($stat->city, $stat->region, $stat->country);

        $data = [
            'data' => [
                'quantity' => [$stat->city, $stat->region, $stat->country, max($arr)*1.1 ]
            ],
            'labels' => [
                'Оренбургская область',
                'Приволжский округ',
                'Российская Федерация',
            ]
        ];

        return json_encode($data);
    }

    function index(){
        $stats = Stat::all();
        return view('cp.stats.index', compact('stats'));
    }

    public function create()
    {

        return view('cp.stats.form');
    }

    public function store(Request $request)
    {
        Stat::create([
            'label' => $request->label,
            'city' => $request->city,
            'region' => $request->region,
            'country' => $request->country,
        ]);

        return redirect()->route('stats.index');
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        $stat = Stat::findOrFail($id);

        return view('cp.stats.form', compact('stat'));
    }

    public function update(Request $request, $stat)
    {
        $stat = Stat::findOrFail($stat);

        $stat->label = $request->label;
        $stat->city = $request->city;
        $stat->region = $request->region;
        $stat->country = $request->country;
        $stat->save();

        return redirect()->route('stats.index');
    }

    public function destroy($id)
    {
        Stat::destroy((int) $id); //удаление

        return redirect()->route('stats.index');
    }

}
