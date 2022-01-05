<?php

namespace App\Http\Controllers;

use App\Model\Selsoviet;
use Illuminate\Http\Request;

class SelsovietController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function create_json(Request $request, $rid)
    {
      $data = new Selsoviet();
      $data->area_id = $rid;
      $data->name = $request->input('name');
      $data->save();

      return response()->json($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Selsoviet  $selsoviet
     * @return \Illuminate\Http\Response
     */
    public function show(Selsoviet $selsoviet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Selsoviet  $selsoviet
     * @return \Illuminate\Http\Response
     */
    public function edit(Selsoviet $selsoviet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Selsoviet  $selsoviet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Selsoviet $selsoviet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Selsoviet  $selsoviet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Selsoviet $selsoviet, $id)
    {
      Selsoviet::destroy((int)$id); //удаление

      return redirect()->back();
    }
}
