<?php

namespace App\Http\Controllers;


use App\Models\Mapping;
use App\Models\System;
use App\Services\UserService;
use Illuminate\Http\Request;

class SystemController extends Controller
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
        $this->authorize('readAny', System::class);
        $systems = System::paginate();

        return view('systems.index', compact('systems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('createAny', System::class);

        return view('systems.create');
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
        $this->authorize('createAny', System::class);
        $this->validate($request, [
            'name' => 'required|max:255|unique:systems,name'
        ]);

        System::create($request->only(['name']));

        return redirect()->route('systems.index')
            ->with('success', 'New system has been created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $system = System::findOrFail($id);
        $this->authorize('update', $system);

        return view('systems.edit', compact('system'));
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
        $system = System::findOrFail($id);
        $this->authorize('update', $system);

        $this->validate($request, [
            'name' => 'required|max:255|unique:systems,name,' . $system->name . ',name'
        ]);


        $system->fill($request->only(['name']))->saveOrFail();

        return redirect()->route('systems.index')
            ->with('success', 'System has been changed.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $system = System::findOrFail($id);
        $this->authorize('delete', $system);

        // TODO: Add password confirmation?
        Mapping::where('ref_id', $id)
            ->where('type', 'system')->delete();

        $system->delete();

        return redirect()->route('systems.index')
            ->with('success', 'System has been deleted.');
    }
}
