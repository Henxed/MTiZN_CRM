<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Regist;
use App\Models\RegistCategory;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RegistController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return Redirect::route('registries', ['npa', 'all']);
    }

    public function list($slug, $parametr)
    {
        $categories = RegistCategory::where('slug', $slug)->first();
        if($parametr === 'all'){
            $arr = Regist::where('category_id', $categories->id)->with('status', 'files')->filter(Request::only('search'))->paginate(15);
        }else{
            $status = Status::where('slug', $parametr)->first();
            if($status){
                $arr = Regist::where('category_id', $categories->id)->where('status_id', $status->code)->with('status')->filter(Request::only('search'))->paginate(15);
            }else{
                return abort(404);
            }
        }

        return Inertia::render('Registry/Index', [
            'filters' => Request::all('search'),
            'regists' => $arr,
            'categories' => RegistCategory::get(),
            'category_title' => $categories->title,
            'category' => $categories->slug,
            'count_all' => Regist::where('category_id', $categories->id)->count(),
            'count_true' => Regist::where('category_id', $categories->id)->where('status_id', 100)->count(),
            'count_change' => Regist::where('category_id', $categories->id)->where('status_id', 101)->count(),
            'count_false' => Regist::where('category_id', $categories->id)->where('status_id', 102)->count(),
            'count_die' => Regist::where('category_id', $categories->id)->where('status_id', 103)->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Registry/Create', [
            'categories' => RegistCategory::get(),
            'statuses' => Status::where('model', 'regist')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Request::validate([
            'title' => ['required', 'max:255'],
            'npa' => ['required', 'max:250', Rule::unique('regists')],
            'depart' => ['nullable'],
            'status_id' => ['required'],
            'category_id' => ['required'],
            'term' => ['nullable'],
            'files' => ['nullable'],
        ]);

        $reg = Regist::create([
            'title' => Request::get('title'),
            'npa' => Request::get('npa'),
            'depart' => Request::get('depart'),
            'status_id' => Request::get('status_id'),
            'category_id' => Request::get('category_id'),
            'term' => Request::get('term'),
        ]);

        if (Request::file('files')) {
            foreach(Request::file('files') as $file)
            {
                $name = Carbon::now('Asia/Yekaterinburg')->format('Y-m-d-H-i-s&'). str_replace(array('_', '-', '—', '  ', ',',' '), '-', trim($file->getClientOriginalName()));
                Storage::putFileAs('public/regist', $file, $name);
                File::create(
                    ['regist_id' => $reg->id, 'file' => 'regist/'.$name, 'size' => $file->getSize()]
                );
            }
        }
        return Redirect::route('registry.index')->with('success', 'Реестр был добавлен!');
    }

    public function edit($id)
    {

        return Inertia::render('Registry/Edit', [
            'regist' => Regist::with('status', 'files')->findOrFail($id),
            'categories' => RegistCategory::get(),
            'statuses' => Status::where('model', 'regist')->get(),
        ]);
    }


    public function update(Regist $regist, $reg)
    {

        $regist->where('id', $reg)->update(Request::only('title','npa','depart','status','term','status_id','category_id'));

        if (Request::file('files')) {
            foreach(Request::file('files') as $file)
            {
                $name = Carbon::now('Asia/Yekaterinburg')->format('Y-m-d-H-i-s--&'). str_replace(array('_', '-', '—', '  ', ',',' '), '-', trim($file->getClientOriginalName()));
                Storage::putFileAs('public/regist', $file, $name);
                File::create(
                    ['regist_id' => $reg, 'file' => 'regist/'.$name, 'size' => $file->getSize()]
                );
            }
        }

        return Redirect::route('registry.edit', $reg)->with('success', 'Реестр был обновлен!');
    }

    public function destroy($reg)
    {
        $regist = Regist::with('files')->findOrFail($reg);
        foreach($regist->files as $file)
        {
            unlink(public_path('storage/'.$file->file));
        }
        $regist->delete();
        return Redirect::route('registry.index')->with('success', 'Реестр удален!');
    }

    public function file_destroy($id)
    {
        $file = File::findOrFail($id);
        unlink(public_path('storage/'.$file->file));
        $file->delete();

        return true;
    }
}
