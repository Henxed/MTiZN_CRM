<?php

namespace App\Http\Controllers;

use App\Models\Regist;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RegistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Registry/Index', [
            'regions' => Regist::paginate(15)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('regist.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Regist::create([
            'title' => Request::get('title'),
            'npa' => Request::get('npa'),
            'depart' => Request::get('depart'),
            'status' => Request::get('status'),
            'file' => Request::file('file') ? Storage::disk('local')->put('regist', Request::file('img')) : null,
        ]);

        return redirect()->route('regist.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Tpl  $modelTpl
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Tpl  $modelTpl
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Regist::findOrFail($id);
        return view('regist.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Tpl  $modelTpl
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tpl $Tpl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Tpl  $modelTpl
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tpl $Tpl)
    {
        //
    }
}
