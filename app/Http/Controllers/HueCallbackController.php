<?php

namespace App\Http\Controllers;

use App\Services\Hue\HueService;
use App\Services\Hue\Requests\CreateUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HueCallbackController extends Controller
{
    public function __invoke(Request $request, HueService $service): JsonResponse
    {
        $request->validate(['code' => 'required', 'string']);

        $service->token()->fetch($request->input('code'));
        $service->configuration()->link();

        $user = $service->configuration()->create(new CreateUser($service->appId));

        return response()->json([
            'data' => [
                'username' => $user->name,
            ],
        ]);
    }
}
