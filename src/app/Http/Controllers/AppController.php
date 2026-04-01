<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dvd;
use App\Models\Tag;
use App\Models\User;
use App\Models\JobStatus;
use App\Models\Genre;
use App\Jobs\FillMeta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
   public function home() {
        $random = Dvd::inRandomOrder()->take(6)->get();

        return view('pages.home.home', [
            'tvCount' => Dvd::where('media_type','=','tv')->count(),
            'movieCount' => Dvd::where('media_type','=','movie')->count(),
            'discCount' => Dvd::count(),
            'dvdCount' => Dvd::where('disc_type','=','dvd')->count(),
            'bluerayCount' => Dvd::where('disc_type','=','blueray')->count(),
            'random' => $random,
        ]);
    }


    public function check(Request $request, $mediaType, $id) {
        $results = Dvd::where('tmdbid','=',$id)->get();

        return view('pages.home.check', [
            'id' => $id,
            'results' => $results,
            'mediaType' => $mediaType
        ]);
    }


    public function addDvd(Request $request) {
        $data = $request->validate([
            'tmdbid' => ['required', 'string'],
            'poster_path' => ['string'],
            'disc_type' => ['required', 'string'],
            'media_type' => ['required', 'string'],
            'title' => ['required', 'string'],
            'season' => ['string','nullable'],
        ]);

        $dvd = Dvd::create($data);
        JobStatus::create([
            'type' => 'FillMeta',
            'reference_id' => $dvd->id,
            'status' => 'pending'
        ]);

        return redirect("/");
    }


    public function requeue(Request $request, $id) {
        JobStatus::create([
            'type' => 'FillMeta',
            'reference_id' => $id,
            'status' => 'pending'
        ]);

        return redirect()->back();
    }


    public function library(Request $request) {

        $search = trim(request('s',''));

        $dvds = Dvd::select('*')
            ->selectRaw("
                CASE
                    WHEN LOWER(title) = ? THEN 3
                    WHEN LOWER(title) LIKE ? THEN 2
                    WHEN LOWER(search_title) LIKE ? THEN 1
                    ELSE 0
                END as relevance
            ", [
                $search,
                "%$search%",
                "%$search%",
            ])
            ->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%$search%"]);
            })
            ->orderByDesc('relevance')
            ->orderByDesc('created_at')
            ->get();

        return view('pages.library.home', [
            'dvds' => $dvds,
            'search' => $search,
        ]);
    }

    public function jobs() {
        if(!Auth::user()->is_admin) { return redirect()->back();}
        return view('pages.jobs.home', [
            'jobs' => JobStatus::orderBy('id', 'desc')->get(),
        ]);
    }

    public function show($id) {
        $dvd =  Dvd::find($id);
        $seasons = null;

        if($dvd->media_type == "tv") {
            $seasons = Dvd::where('tmdbid','=', $dvd->tmdbid)->get();
        }
        

        return view('pages.dvd.show', [
            "seasons" => $seasons,
            'dvd' => $dvd,
        ]);
    }

    public function edit($id) {
        $dvd =  Dvd::find($id);
        $seasons = null;

        if($dvd->media_type == "tv") {
            $seasons = Dvd::where('tmdbid','=', $dvd->tmdbid)->get();
        }
        

        return view('pages.dvd.edit', [
            "seasons" => $seasons,
            'tags' => Tag::get(),
            'dvd' => $dvd,
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
            'tags' => ['nullable', 'string'],
            'search_title' => ['nullable','string'],
            'poster_path' => ['nullable','string'],
            'release' => ['nullable','string'],
            'season' => ['nullable','string'],
            'season_name' => ['nullable','string'],
            'series_min' => ['nullable','integer'],
            'series_max' => ['nullable','integer'],
            'collection_id' => ['nullable','string'],
            'collection_title' => ['nullable','string'],
        ]);

        $dvd = Dvd::find($id);
        $dvd->update($data); 

        $dvd->tags()->detach();
        foreach(explode(",", $data['tags']) as $tag) {
            if(strlen(trim($tag)) > 0) {
                $dvd->tags()->attach(Tag::firstOrCreate(['name' => trim($tag)]));
            }
        }

        Tag::doesntHave('dvds')->delete();


        return redirect("/dvd/".$id);
    }



    public function rnd($type) {
        switch($type) {

            case "movie":
                $dvd = Dvd::where('media_type','=','movie')->inRandomOrder()->first();
                return redirect("/dvd/" . $dvd->id);
                break;

            case "tv":
                $dvd = Dvd::where('media_type','=','tv')->inRandomOrder()->first();
                $dvd = Dvd::where('tmdbid','=',$dvd->tmdbid)->orderBy('season')->first();
                return redirect("/dvd/" . $dvd->id);
                break;

            case "dvd":
                $dvd = Dvd::where('disc_type','=','dvd')->inRandomOrder()->first();
                if($dvd->season) {
                    $dvd = Dvd::where('tmdbid','=',$dvd->tmdbid)->where('disc_type','=','dvd')->orderBy('season')->first();
                }
                return redirect("/dvd/" . $dvd->id);
                break;

            case "blueray":
                $dvd = Dvd::where('disc_type','=','blueray')->inRandomOrder()->first();
                if($dvd->season) {
                    $dvd = Dvd::where('tmdbid','=',$dvd->tmdbid)->where('disc_type','=','blueray')->orderBy('season')->first();
                }
                return redirect("/dvd/" . $dvd->id);
                break;

            default:
                $dvd = Dvd::inRandomOrder()->first();
                if($dvd->season) {
                    $dvd = Dvd::where('tmdbid','=',$dvd->tmdbid)->orderBy('season')->first();
                }
                return redirect("/dvd/" . $dvd->id);
                break;
        }
    }

    public function import() {
        if(!Auth::user()->is_admin) { return redirect()->back();}
        $export = [];
        foreach(Dvd::get() as $row) {
            array_push($export, implode(",",[
                $row['tmdbid'],
                $row['media_type'],
                $row['disc_type'],
                $row['title'],
                $row['season'],
            ]));
        }
        return view('pages.dvd.import', ['export' => implode("\n", $export)]);
    }

    public function runImport(Request $request)
    {
        if(!Auth::user()->is_admin) { return redirect()->back();}
        $validated = $request->validate([
            'csv' => ['required', 'string'],
        ]);

        $columns = ["tmdbid", "media_type", "disc_type", "title", "season"];

        $lines = preg_split("/\r\n|\n|\r/", trim($validated['csv']));
        $results = [];

        foreach ($lines as $lineNumber => $line) {
            try {
                $row = str_getcsv($line);

                $dataset = array_combine(
                    $columns,
                    array_pad($row, count($columns), null)
                );

                $dvd = Dvd::create($dataset);
                
                JobStatus::create([
                    'type' => 'FillMeta',
                    'reference_id' => $dvd->id,
                    'status' => 'pending'
                ]);

                $results[] = [
                    ...$dataset,
                    'success' => true,
                    'message' => 'Imported',
                    'dvd_id' => $dvd->id,
                    'line' => $lineNumber + 1,
                ];
            } catch (\Throwable $e) {
                $results[] = [
                    ...$dataset,
                    'success' => false,
                    'message' => $e->getMessage(),
                    'dvd_id' => null,
                    'line' => $lineNumber + 1,
                ];
            }
        }

        return view('pages.dvd.import-status', [
            'results' => $results,
        ]);
    }

    public function settings() {
        return view('pages.settings.home');
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



    public function collection($id) {

        $apiKey = config('app.tmdb_api_key');
        $baseUrl = "https://api.themoviedb.org/3/collection/${id}}?api_key=${apiKey}";

        $response = Http::timeout(20)
            ->acceptJson()
            ->get($baseUrl, [
                'api_key' => $apiKey,
            ])
            ->throw();

        $data = $response->json();

        $dvds = Dvd::where('collection_id',$id)->orderByDesc('created_at')->get();

        return view('pages.collections.home', [
            'dvds' => $dvds,
            'parts' => $data['parts']
        ]);
    }



    public function genres($id) {
        $genre = Genre::find($id);
        $dvds = $genre->dvds()->orderByDesc('created_at')->get();

        return view('pages.genres.home', [
            'genre' => $genre,
            'dvds' => $dvds
        ]);
    }

    public function tags($id) {
        $tag = Tag::find($id);
        $dvds = $tag->dvds()->orderByDesc('created_at')->get();

        return view('pages.tags.home', [
            'tag' => $tag,
            'dvds' => $dvds
        ]);
    }

    public function users() {
        if(!Auth::user()->is_admin) { return redirect()->back();}
        return view('pages.settings.users', [
            'users' => User::get(),
        ]);
    }



    public function addUser(Request $request) {
        if(!Auth::user()->is_admin) { return redirect()->back();}

        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'is_admin' => ['required', 'boolean'],
        ]);

        $user = User::create($data);

        return redirect("/settings/users");
    }


    public function saveUser(Request $request,$id) {
        if(Auth::user()->id == $id || !Auth::user()->is_admin) { return redirect()->back();}

        $user = User::find($id);

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable'],
            'is_admin' => ['required', 'boolean'],
        ]);

        $user->username = $validated['username'];
        $user->is_admin = $validated['is_admin'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated.');
    }

    public function deleteUser($id) {
        if(Auth::user()->id == $id || !Auth::user()->is_admin) { return redirect()->back();}
        $user = User::find($id);
        $user->delete();
        return back()->with('success', 'User deleted.');
    }


    public function collections() {
        $collections = Dvd::whereNotNull('collection_id')->get()->groupBy('collection_id')
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'collection_id' => $first->collection_id,
                    'collection_title' => $first->collection_title,
                    'poster_path' => $first->poster_path,
                ];
            })
            ->values()
            ->toArray();

        return view('pages.collections.list', [
            'collections' => $collections
        ]);
    }



    public function shows() {
        $shows = Dvd::whereNotNull('season')->whereNotNull('season_name')->get()->groupBy('tmdbid')
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'dvd_id' => $first->id,
                    'title' => $first->title,
                    'poster_path' => $first->poster_path,
                ];
            })
            ->values()
            ->toArray();

        return view('pages.shows.list', [
            'shows' => $shows
        ]);
    }

}
