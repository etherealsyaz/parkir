<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleType::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('jenis', 'like', '%' . $request->search . '%');
        }
        $vehicleTypes = $query->get();
        return view('vehicle-type.index', compact('vehicleTypes'));
    }

    public function create()
    {
        return view('vehicle-type.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis'             => 'required|in:motorcycle,car,other',
            'perjam_pertama'    => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari'       => 'required|integer|min:0',
        ]);

        VehicleType::create($request->all());

        return redirect()->route('vehicle-type.index')
            ->with('success', 'New Vehicle Type was successfully saved!');
    }

    public function edit(VehicleType $vehicleType)
    {
        return view('vehicle-type.edit', compact('vehicleType'));
    }

    public function update(Request $request, VehicleType $vehicleType)
    {
        $request->validate([
            'jenis'             => 'required|in:motorcycle,car,other',
            'perjam_pertama'    => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari'       => 'required|integer|min:0',
        ]);

        $vehicleType->update($request->all());

        return redirect()->route('vehicle-type.index')
            ->with('success', 'Vehicle Type updated!');
    }

    public function destroy(VehicleType $vehicleType)
    {
        $vehicleType->delete();
        return redirect()->route('vehicle-type.index')
            ->with('success', 'Vehicle Type deleted!');
    }
}