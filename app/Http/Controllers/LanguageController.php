<?php

namespace App\Http\Controllers;


use App\Models\Language;
use App\Models\Mapping;
use App\Policies\LanguagePolicy;
use App\Services\UserService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('readAny', Language::class);
        $languages = Language::paginate();

        return view('languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('createAny', Language::class);

        return view('languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserService $service
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('createAny', Language::class);
        $this->validate($request, [
            'code' => 'required|max:5|unique:languages,code',
            'name' => 'required|max:255'
        ]);

        Language::create($request->only(['code','name']));

        return redirect()->route('languages.index')
            ->with('success', 'New language has been created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $language = Language::findOrFail($id);
        $this->authorize('update', $language);

        return view('languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        $this->authorize('update', $language);

        $this->validate($request, [
            'code' => 'required|max:5|unique:languages,code,' . $language->code . ',code',
            'name' => 'required|max:255'
        ]);


        $language->fill($request->only(['code','name']))->saveOrFail();

        return redirect()->route('languages.index')
            ->with('success', 'Language has been changed.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $language = Language::findOrFail($id);
        $this->authorize('delete', $language);

        // TODO: Add password confirmation?

        Mapping::where('ref_id', $id)
            ->where('type', 'language')->delete();

        $language->delete();

        return redirect()->route('languages.index')
            ->with('success', 'Language has been removed.');
    }
}
