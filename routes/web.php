<?php

use App\Http\Controllers\PlaceController;
use Illuminate\Support\Facades\Route;

class PlaceController
{
    // Suggestion flow: members submit places (status pending)
    public function createSuggestion()
    {
        return view('places.create');
    }

    // Store suggestion with pending status
    public function storeSuggestion(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/places');
            $validated['image'] = basename($path);
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        Place::create($validated);

        return redirect()->route('places.index')->with('status', 'Tempat diajukan untuk verifikasi admin.');
    }

    // Admin actions
    // List pending places for admin review
    public function adminIndex()
    {
        $places = Place::where('status', 'pending')->get();
        return view('admin.places.pending', compact('places'));
    }

    // Approve a pending place
    public function approve(Place $place)
    {
        $place->status = 'approved';
        $place->save();
        return Redirect::back();
    }

    // Reject a pending place
    public function reject(Place $place)
    {
        $place->status = 'rejected';
        $place->save();
        return Redirect::back();
    }
}
