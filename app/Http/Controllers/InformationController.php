<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    private $filePath = 'info.html'; // Path inside storage/app/

    public function index()
    {
        // Check if file exists, otherwise use default content
        $content = Storage::exists($this->filePath) 
            ? Storage::get($this->filePath) 
            : '<p>Welcome to our information page!</p>';

        return view('information', compact('content'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        // Save the content to a file
        Storage::put($this->filePath, $request->content);

        return redirect()->route('info.index')->with('success', 'Information updated successfully!');
    }
}