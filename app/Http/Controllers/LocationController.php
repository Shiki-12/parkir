<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $locations = Location::when($search, function ($query, $search) {
            return $query->where('location_name', 'like', '%' . $search . '%');
        })->latest()->get();

        return view('locations.index', [
            'title' => 'Location',
            'locations' => $locations,
            'search' => $search,
        ]);
    }


    public function create()
    {
        return view('locations.create', [
            'title' => 'Add Location',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_name' => 'required|string|max:100',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car' => 'required|integer|min:0',
            'max_other' => 'required|integer|min:0',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'New Location was successfully saved!!');
    }

    public function edit(int $id)
    {
        $location = Location::findOrFail($id);

        return view('locations.edit', [
            'title' => 'Edit Location',
            'location' => $location,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'location_name' => 'required|string|max:100',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car' => 'required|integer|min:0',
            'max_other' => 'required|integer|min:0',
        ]);

        $location = Location::findOrFail($id);
        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Location was successfully updated!!');
    }

    public function destroy(int $id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return redirect()->back()
            ->with('success', 'Location was successfully deleted!!');
    }
}
