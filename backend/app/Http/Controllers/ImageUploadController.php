<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Validate input
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'headline' => 'required|string|max:255',
            'details' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            // Retrieve file
            $file = $request->file('file');
            $filename =  $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);

            // Save to database
            $image = new Image();
            $image->filename = $filename;
            $image->headline = $request->input('headline');
            $image->details = $request->input('details');
            $image->tags = $request->input('tags');
            $image->save();

            return response()->json([
                'testing'=>'test',
                'message' => 'Image uploaded successfully',
                'filename' => $filename,
                'url' => asset('images/' . $filename),
            ], 200);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }
    public function listImages()
    {
        $images = Image::all();
        return response()->json($images);
    }
    
    public function deleteImage($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
    
        $filePath = public_path('images/' . $image->filename);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    
        $image->delete();
    
        return response()->json(['message' => 'Image deleted successfully']);
    }
    
    public function updateImage(Request $request, $id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
    
        $request->validate([
            'headline' => 'required|string|max:255',
            'details' => 'required|string',
            'tags' => 'nullable|string',
        ]);
    
        $image->update([
            'headline' => $request->headline,
            'details' => $request->details,
            'tags' => $request->tags,
        ]);
    
        return response()->json(['message' => 'Image updated successfully']);
    }
    
}
