<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function () {
    Route::get('/', function () {
        [, $query] = explode('?', request()->fullUrl() . '?');
        return Http::withHeaders([
            'Authorization' => request()->header('Authorization')
        ])
            ->get("https://data.cityofchicago.org/resource/w22p-bfyb.json?$query")
            ->json();
    });

    Route::get('/postal', function () {
        [, $query] = explode('?', request()->fullUrl() . '?');

        $data = Http::withHeaders([
            'Authorization' => request()->header('Authorization')
        ])
            ->get("https://data.cityofchicago.org/resource/unjd-c2ca.json?$query")
            ->json();

        foreach ($data as &$d) {
            unset($d['the_geom']);
            $d['count'] = count($data);
        }

        return $data;
    });
});
