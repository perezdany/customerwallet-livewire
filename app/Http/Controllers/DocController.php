<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DocController extends Controller
{
    //Handle all documentaions


    public function RetriveGuide(Request $request)
    {
        if(Storage::disk('local')->exists($request->file))
        {
            //return Storage::download($request->file);

            return response()->file(Storage::path($request->file));
        }
        else
        {
            return redirect('welcome')->with('error', 'Le fichier n\'existe pas');
        }
    }
}
