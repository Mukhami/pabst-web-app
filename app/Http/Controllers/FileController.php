<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function downloadFile(File $file): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::download($file->path, $file->name);
    }

    public function deleteFile(File $file): \Illuminate\Http\RedirectResponse
    {
        // Delete the file from storage
        Storage::delete($file->path);
        // Delete the file record from the database
        $file->delete();

        // Redirect back to the matter request show page with a success message
        return Redirect::back()->with('success', $file->name . ' has been deleted successfully');
    }
}
