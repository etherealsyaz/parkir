<?php
namespace App\Http\Controllers;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('location_name', 'like', '%' . $request->search . '%');
        }
        $locations = $query->get();
        return view('location.index', compact('locations'));
    }

    public function create()
    {
        return view('location.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:100',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car'        => 'required|integer|min:0',
            'max_other'      => 'required|integer|min:0',
        ]);
        Location::create($request->all());
        return redirect()->route('location.index')
            ->with('success', 'New Location was successfully saved!');
    }

    public function edit(Location $location)
    {
        return view('location.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'location_name' => 'required|string|max:100',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car'        => 'required|integer|min:0',
            'max_other'      => 'required|integer|min:0',
        ]);
        $location->update($request->all());
        return redirect()->route('location.index')
            ->with('success', 'Location updated successfully!');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('location.index')
            ->with('success', 'Location deleted!');
    }

    public function report()
    {
        $locations = Location::all();
        return view('report.location', compact('locations'));
    }
}
