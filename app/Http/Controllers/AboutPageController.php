<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutPage;

class AboutPageController extends Controller
{
    public function show()
    {
        $about = AboutPage::first(); // only one page
        return view('about.show', compact('about'));
    }

    public function edit()
    {
        $about = AboutPage::first();
        return view('about.edit', compact('about'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $about = AboutPage::first();
        if (!$about) {
            $about = new AboutPage();
        }

        $about->content = $request->content;
        $about->save();

        return redirect()->route('about.show')->with('success', 'About page updated.');
    }
}
