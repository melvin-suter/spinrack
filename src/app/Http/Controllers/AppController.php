<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dvd;
use Illuminate\Support\Facades\Hash;

class AppController extends Controller
{
    public function home() {
        $dvds = Dvd::latest()->take(10)->get();
        return view('pages.home.home', [
            'dvds' => $dvds,
        ]);
    }

    public function check($mediaType, $id) {
        $results = Dvd::where('tmdbid','=',$id)->get();
        return view('pages.home.check', [
            'id' => $id,
            'results' => $results,
            'mediaType' => $mediaType,
        ]);
    }

    public function addDvd(Request $request) {
        $data = $request->validate([
            'tmdbid' => ['required', 'string'],
            'poster_path' => ['string'],
            'backdrop_path' => ['string'],
            'overview' => ['string'],
            'disc_type' => ['required', 'string'],
            'season' => ['nullable', 'integer'],
            'title' => ['required', 'string'],
            'release' => ['required', 'string'],
        ]);

        Dvd::create($data); 

        return redirect("/");
    }

    public function edit($id) {
        return view('pages.dvd.edit', [
            'dvd' => Dvd::find($id),
        ]);
    }

    public function delete($id) {
        Dvd::find($id)->delete();
        return redirect("/");
    }

     public function save(Request $request, $id) {
        $data = $request->validate([
            'disc_type' => ['required', 'string'],
            'season' => ['nullable', 'integer'],
        ]);

        Dvd::find($id)->update($data); 

        return redirect("/");
    }


    public function search(Request $request) {

        $search = trim(request('s',''));

        $dvds = Dvd::select('*')
            ->selectRaw("
                CASE
                    WHEN LOWER(title) = ? THEN 3
                    WHEN LOWER(title) LIKE ? THEN 2
                    WHEN LOWER(overview) LIKE ? THEN 1
                    ELSE 0
                END as relevance
            ", [
                $search,
                "%$search%",
                "%$search%"
            ])
            ->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%$search%"])
                ->orWhereRaw('LOWER(overview) LIKE ?', ["%$search%"]);
            })
            ->orderByDesc('relevance')
            ->orderByDesc('created_at')
            ->get();

        return view('pages.dvd.search', [
            'dvds' => $dvds,
            'search' => $search,
        ]);
    }

    public function settings() {
        return view('pages.home.settings');
    }

    public function settingsSave(Request $request) {
        $user = auth()->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed'],
        ]);

        $user->username = $validated['username'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated.');
    }
}
