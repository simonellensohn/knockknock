<?php

namespace App\Http\Controllers;

use App\Services\Hue\HueApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HueCallbackController extends Controller
{
    public function __invoke(Request $request, HueApi $hueApi): JsonResponse
    {
        $request->validate(['code' => 'required', 'string']);

        $hueApi->fetchAccessToken($request->input('code'));

        return response()->json([
            'data' => [
                'username' => $hueApi->createUser(),
            ],
        ]);
    }
}
