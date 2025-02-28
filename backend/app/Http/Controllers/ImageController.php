<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // Uploading new image
    public function store(StoreImageRequest $request)
    {
        $path = $request->file('image')->store('images', 'public');

        $image = Image::create([
            'user_id' => $request->user()->id,
            'caption' => $request->caption,
            'path' => $path,
            'visibility' => $request->visibility,
        ]);

        return response()->json($image, 201);
    }


    // SHow
    public function show(Image $image)
    {
        return response()->json($image);
    }

  // Index
    // only public images
    public function publicIndex()
    {
        return response()->json(Image::where('visibility', 'public')->get());
    }

    // images for the authenticated user
    public function index(Request $request)
    {
        $images = Image::where('user_id', $request->user()->id)->get();
        return response()->json($images);
    }

    // Toggle visibility (public/private)
    public function toggleVisibility(Request $request, Image $image)
    {
        if ($image->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $image->update(['visibility' => $image->visibility === 'public' ? 'private' : 'public']);

        return response()->json($image);
    }

    // Delete
    public function destroy(Request $request, Image $image)
    {
        if ($image->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json(['message' => 'Image deleted']);
    }
}
