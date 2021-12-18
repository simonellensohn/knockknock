<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PushSubscriptionController extends Controller
{
    public function update(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);

        $request->user()->updatePushSubscription(
            $request->endpoint,
            $request->publicKey,
            $request->authToken,
            $request->contentEncoding
        );

        return Redirect::back();
    }

    public function destroy(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);

        $request->user()->deletePushSubscription($request->endpoint);

        return Redirect::back();
    }
}
