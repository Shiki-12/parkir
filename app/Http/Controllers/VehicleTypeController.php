<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');

        $vehicleTypes = VehicleType::when($search, function ($query, $search) {
            return $query->where('jenis', 'like', '%' . $search . '%');
        })->latest()->get();

        return view('vehicle-types.index', [
            'title' => 'Vehicle Type',
            'vehicleTypes' => $vehicleTypes,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('vehicle-types.create', [
            'title' => 'Add Vehicle Type',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:motorcycle,car,other',
            'perjam_pertama' => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari' => 'required|integer|min:0',
        ]);

        VehicleType::create($validated);

        return redirect()->route('vehicle-types.index')
            ->with('success', 'New Vehicle Type was successfully saved!');
    }


    public function edit(int $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        return view('vehicle-types.edit', [
            'title' => 'Edit Vehicle Type',
            'vehicleType' => $vehicleType,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:motorcycle,car,other',
            'perjam_pertama' => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari' => 'required|integer|min:0',
        ]);

        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->update($validated);

        return redirect()->route('vehicle-types.index')
            ->with('success', 'Vehicle Type was successfully updated!');
    }

    public function destroy(int $id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->delete();

        return redirect()->back()
            ->with('success', 'Vehicle Type was successfully deleted!');
    }
}
