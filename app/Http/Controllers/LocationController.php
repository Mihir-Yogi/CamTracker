<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Depot;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with('depot')->paginate(10); 
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        $depots = Depot::all();
        return view('admin.locations.location_add', compact('depots'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string', 'depot_id' => 'required|exists:depots,id']);
        Location::create($request->all());
        return redirect()->route('admin.locations.index')->with('status', 'Depot Added successfully!');
    }

    // public function show(Location $location)
    // {
    //     return view('locations.show', compact('location'));
    // }

    public function edit(Location $location)
    {
        $depots = Depot::all();
        return view('admin.locations.location_edit', compact('location', 'depots'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate(['name' => 'required|string', 'depot_id' => 'required|exists:depots,id']);
        $location->update($request->all());
        return redirect()->route('admin.locations.index')->with('status', 'Depot Updated successfully!');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('admin.locations.index')->with('status', 'Depot Deleted successfully!');
    }

}
