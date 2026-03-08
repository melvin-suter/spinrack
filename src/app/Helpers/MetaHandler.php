<?php

namespace App\Helpers;

use App\Models\Dvd;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class MetaHandler
{
    public static function run(int $dvdID): void
    {
        try {
            $dvd = Dvd::find($dvdID);

            if (!$dvd) {
                throw new \RuntimeException("DVD not found for ID {$dvdID}");
            }

            if (!$dvd->tmdbid) {
                throw new \RuntimeException("DVD {$dvdID} has no TMDB ID");
            }

            if (!in_array($dvd->media_type, ['movie', 'tv'], true)) {
                throw new \RuntimeException(
                    "DVD {$dvdID} has invalid media_type '{$dvd->media_type}'. Expected 'movie' or 'tv'."
                );
            }

            $apiKey = config('app.tmdb_api_key');
            if (!$apiKey) {
                throw new \RuntimeException("TMDB API key is not configured");
            }

            $type = $dvd->media_type;
            $baseUrl = "https://api.themoviedb.org/3/{$type}/{$dvd->tmdbid}";

            $response = Http::timeout(20)
                ->acceptJson()
                ->get($baseUrl, [
                    'api_key' => $apiKey,
                ])
                ->throw();

            $data = $response->json();

            if (!is_array($data)) {
                throw new \RuntimeException("TMDB returned invalid JSON for DVD {$dvdID}");
            }

            $languages = collect(explode(',', (string) config('app.tmdb_languages', 'en-US')))
                ->map(fn ($lang) => trim($lang))
                ->filter()
                ->unique()
                ->values();

            if ($languages->isEmpty()) {
                $languages = collect(['en-US']);
            }

            $titles = collect();

            foreach ($languages as $lang) {
                try {
                    $translationResponse = Http::timeout(20)
                        ->acceptJson()
                        ->get($baseUrl, [
                            'api_key' => $apiKey,
                            'language' => $lang,
                        ])
                        ->throw();

                    $translated = $translationResponse->json();

                    if (!is_array($translated)) {
                        throw new \RuntimeException("Invalid JSON in TMDB translation response for language {$lang}");
                    }

                    $title = $translated['title'] ?? $translated['name'] ?? null;

                    if ($title && is_string($title)) {
                        $titles->push($title);
                    }
                } catch (RequestException $e) {
                    $tmdbMessage = self::extractTmdbError($e->response?->json(), $e->response?->body());

                    throw new \RuntimeException(
                        "TMDB translation request failed for DVD {$dvdID}, language {$lang}, HTTP {$e->response?->status()}: {$tmdbMessage}",
                        previous: $e
                    );
                } catch (\Throwable $e) {
                    throw new \RuntimeException(
                        "Failed processing TMDB translation for DVD {$dvdID}, language {$lang}: {$e->getMessage()}",
                        previous: $e
                    );
                }
            }

            if(!isset($firstTitle)) {
                $firstTitle = $data['title'] ?? $data['name'] ?? null;
            }

            $baseTitle = $data['title'] ?? $data['name'] ?? null;
            if ($baseTitle && is_string($baseTitle)) {
                $titles->push($baseTitle);
            }

            $searchTitle = $titles
                ->map(fn ($title) => trim($title))
                ->filter()
                ->unique()
                ->implode(', ');

            $seriesMin = null;
            $seriesMax = null;

            if ($type === 'tv') {
                if (!isset($data['seasons']) || !is_array($data['seasons'])) {
                    throw new \RuntimeException("TMDB TV response for DVD {$dvdID} does not contain a valid seasons array");
                }

                $seasonNumbers = collect($data['seasons'])
                    ->pluck('season_number')
                    ->filter(fn ($season) => is_numeric($season) && (int) $season > 0)
                    ->map(fn ($season) => (int) $season)
                    ->values();

                if ($seasonNumbers->isNotEmpty()) {
                    $seriesMin = $seasonNumbers->min();
                    $seriesMax = $seasonNumbers->max();
                }

                $seasonName = $data['seasons'][(int)$dvd->season]['name'] ?? null;

                if($seasonName == null && $dvd->season != null) {
                    $seasonName = "Season ".$dvd->season;
                }
            }

            $updated = $dvd->update([
                'search_title'     => $searchTitle ?: null,
                'poster_path'      => $data['poster_path'] ?? null,
                'overview'         => $data['overview'] ?? null,
                'release'          => $data['release_date'] ?? $data['first_air_date'] ?? null,
                'series_min'       => $seriesMin,
                'series_max'       => $seriesMax,
                'season_name'      => $seasonName ?? null,
                'collection_id'    => isset($data['belongs_to_collection']['id'])
                    ? (string) $data['belongs_to_collection']['id']
                    : null,
                'collection_title' => $data['belongs_to_collection']['name'] ?? null,
            ]);

            if(isset($firstTitle) && $dvd->title == null) {
                $updated = $dvd->update([
                    'title' => $firstTitle
                ]);
            }

            if (!$updated) {
                throw new \RuntimeException("Failed updating DVD {$dvdID} with TMDB metadata");
            }
        } catch (RequestException $e) {
            $tmdbMessage = self::extractTmdbError($e->response?->json(), $e->response?->body());

            throw new \RuntimeException(
                "TMDB request failed for DVD {$dvdID}, HTTP {$e->response?->status()}: {$tmdbMessage}",
                previous: $e
            );
        } catch (\Throwable $e) {
            throw new \RuntimeException(
                "MetaHandler failed for DVD {$dvdID}: {$e->getMessage()}",
                previous: $e
            );
        }
    }

    private static function extractTmdbError(mixed $json, ?string $rawBody = null): string
    {
        if (is_array($json)) {
            if (!empty($json['status_message'])) {
                return (string) $json['status_message'];
            }

            if (!empty($json['message'])) {
                return (string) $json['message'];
            }

            return json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: 'Unknown TMDB JSON error';
        }

        if ($rawBody) {
            return mb_substr(trim($rawBody), 0, 500);
        }

        return 'Unknown TMDB error';
    }
}