<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use Illuminate\Http\Request;

class DepotController extends Controller
{
    public function index()
    {
        $depots = Depot::paginate(10); // Paginate results
        return view('admin.depots.index', compact('depots'));
    }

    public function create()
    {
        return view('admin.depots.depot_add');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
            ]);
        Depot::create($request->all());
        return redirect()->back()->with('status', 'Depot Added successfully!');
    }

    public function show(Depot $depot)
    {
        return view('depots.depots.show', compact('depot'));
    }

    public function edit(Depot $depot)
    {
        return view('admin.depots.depot_edit', compact('depot'));
    }

    public function update(Request $request, Depot $depot)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $depot->update($request->all());
        return redirect()->route('admin.depots.index')->with('status', 'Depot Updated successfully!');
    }

    public function destroy(Depot $depot)
{
    // Delete all related NVRs
    foreach ($depot->nvrs as $nvr) {
        $nvr->delete();
    }

    // Delete all related DVRs
    foreach ($depot->dvrs as $dvr) {
        $dvr->delete();
    }

    // Delete all related HDDs
    foreach ($depot->hdds as $hdd) {
        $hdd->delete();
    }

    // Delete all related Combos
    foreach ($depot->combos as $combo) {
        $combo->delete();
    }

    // Delete all related CCTVs if applicable (you may need to adjust if 'cctvs' is the correct name of the relationship)
    foreach ($depot->cctvs as $cctv) {
        $cctv->delete();
    }

    // Finally, delete the Depot
    $depot->delete();

    return redirect()->route('admin.depots.index')->with('status', 'Depot and its related records deleted successfully!');
}
}
