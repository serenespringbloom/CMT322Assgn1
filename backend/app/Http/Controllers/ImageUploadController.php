<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('file')) {
            // Retrieve the uploaded file
            $file = $request->file('file');
            
            // Generate a unique filename
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Move the file to the public/images directory
            $file->move(public_path('images'), $filename);

            // Save filename to the database
            $image = new Image();
            $image->filename = $filename;
            $image->save();

            return response()->json([
                'message' => 'Image uploaded successfully',
                'filename' => $filename,
                'url' => asset('images/' . $filename)
            ], 200);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }
}
