<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $places = Place::all();
        $categories = Place::select('category')->distinct()->pluck('category');
        if (auth()->check()) {
            return view('places.index', compact('places', 'categories'));
        }
        return view('welcome', compact('places', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('places.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only('name', 'category', 'description', 'latitude', 'longitude');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('places', 'public');
            $data['image'] = $imagePath;
        }

        Place::create($data);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        return view('places.edit', compact('place'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only('name', 'category', 'description', 'latitude', 'longitude');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($place->image && Storage::disk('public')->exists($place->image)) {
                Storage::disk('public')->delete($place->image);
            }
            $imagePath = $request->file('image')->store('places', 'public');
            $data['image'] = $imagePath;
        }

        $place->update($data);

        return redirect()->route('places.index')->with('success', 'Tempat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        if ($place->image && Storage::disk('public')->exists($place->image)) {
            Storage::disk('public')->delete($place->image);
        }

        $place->delete();

        return redirect()->route('places.index')->with('success', 'Tempat berhasil dihapus.');
    }
}
