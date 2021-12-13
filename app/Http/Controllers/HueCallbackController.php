<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Services\Hue\HueApi;
use Illuminate\Http\Request;

class HueCallbackController extends Controller
{
    public function __invoke(Request $request, HueApi $hueApi)
    {
        if ($code = $request->input('code')) {
            $hueApi->fetchAccessToken($code);

            return response()->json([
                'data' => [
                    'username' => $hueApi->createUser(),
                ],
            ]);
        }

        abort(400);
    }
}
