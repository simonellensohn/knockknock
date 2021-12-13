<?php

namespace App\Http\Controllers;

use App\Services\Hue\HueApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HueCallbackController extends Controller
{
    public function __invoke(Request $request, HueApi $hueApi)
    {
        if (Storage::exists('hue.json')) {
            abort(400, 'Config file already exists, aborting.');
        }

        if ($code = $request->input('code')) {
            $hueApi->fetchAccessToken($code);

            return response()->json([
                'data' => [
                    'username' => $hueApi->createUser(),
                ],
            ]);
        }

        abort(400, 'Invalid request, code missing.');
    }
}
