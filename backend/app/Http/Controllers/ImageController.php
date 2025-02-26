<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // Uploading new image
    public function store(Request $request)
    {
        $request->validate([
            'image'   => 'required|image|max:5120', // max size 5MB
            'caption' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,private', // require public or private
        ]);

        // Store the image on the 'public' disk in the 'images' folder
        $path = $request->file('image')->store('images', 'public');

        // Create a record in the database; assumes user is authenticated
        $image = Image::create([
            'user_id' => $request->user()->id,
            'caption' => $request->caption,
            'path'    => $path,
            'visibility' => $request->visibility, // store visibility from the request
        ]);

        return response()->json($image, 201);
    }

  // Index
    public function index(Request $request)
    {
        if ($request->user()) {
            // Authenticated: Return both public and private img
            $images = Image::where('user_id', $request->user()->id)->get();
        } else {
            // Unauthenticated: Return only public images
            $images = Image::where('visibility', 'public')->get();
        }
        return response()->json($images);
    }

    // Visibility
    public function toggleVisibility(Request $request, $id)
  {
    $image = Image::findOrFail($id);

    // Ensure the image belongs to the authenticated user.
    if ($image->user_id !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Toggle visibility.
    $newVisibility = $image->visibility === 'public' ? 'private' : 'public';
    $image->update(['visibility' => $newVisibility]);

    return response()->json($image);
  }


    // Delete
    public function destroy(Request $request, $id)
    {
        $image = Image::findOrFail($id);

        // Ensure the image belongs to the authenticated user
        if ($image->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Remove the file from storage
        Storage::disk('public')->delete($image->path);
        // Delete the database record
        $image->delete();

        return response()->json(['message' => 'Image deleted']);
    }
}
