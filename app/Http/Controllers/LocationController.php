<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $locations = Location::sorted()->paginate(10);
        return view('locations.index', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'building' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
        ]);

        try {
            Location::create([
                'building' => ucfirst($request->building),
                'unit' => ucfirst($request->unit),
            ]);
            return response()->json(['success' => 'Location created successfully.']);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create location. ' . $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $location = Location::findOrFail($id);
        return response()->json($location);
    }

    public function edit(Location $location)
    {

        return view('locations.edit', ['location' => $location]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)

    {
        $request->validate([
            'faculty' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
        ]);



        $location = Location::findOrFail($id);
        $location->update([
            'faculty' => ucfirst($request->faculty),
            'unit' => ucfirst($request->unit),
        ]);

        return response()->json(['success' => 'Location updated successfully.']);
        /*return redirect()->route('locations.index')->with('success', 'Location updated successfully.');*/
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $location = Location::findOrFail($id);
        $location->delete();

        return response()->json(['success' => 'Location deleted successfully.']);
    }

    public function getUnitsByBuilding($building): \Illuminate\Http\JsonResponse
    {
        $unit = Location::getUnitsByBuilding($building);
        return response()->json(['unit' => $unit], 200);
    }

}
