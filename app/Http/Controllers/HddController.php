<?php

namespace App\Http\Controllers;

use App\Models\Hdd;
use Illuminate\Http\Request;

class HddController extends Controller
{
    public function index()
    {
        $hdds = Hdd::all();
        return view('admin.HDDs.index', compact('hdds'));
    }

    public function create()
    {
        return view('hdds.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'purchase_date' => 'required|date',
            'install_date' => 'required|date',
            'expiry_date' => 'required|date',
            'warranty_period' => 'required|integer|min:1',
        ]);

        Hdd::create($request->all());
        return redirect()->route('hdds.index');
    }

    public function show(Hdd $hdd)
    {
        return view('hdds.show', compact('hdd'));
    }

    public function edit(Hdd $hdd)
    {
        return view('hdds.edit', compact('hdd'));
    }

    public function update(Request $request, Hdd $hdd)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'purchase_date' => 'required|date',
            'install_date' => 'required|date',
            'expiry_date' => 'required|date',
            'warranty_period' => 'required|integer|min:1',
        ]);

        $hdd->update($request->all());
        return redirect()->route('hdds.index');
    }

    public function destroy(Hdd $hdd)
    {
        $hdd->delete();
        return redirect()->route('hdds.index');
    }
}
