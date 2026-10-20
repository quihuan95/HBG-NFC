<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'url:http,https', 'max:2048'],
            'keys' => ['required', 'array'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
        ]);

        PushSubscription::query()->updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            ['keys' => $validated['keys']],
        );

        return response()->json(['message' => 'Đăng ký nhận thông báo thành công.']);
    }

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'url:http,https', 'max:2048'],
        ]);

        PushSubscription::query()->where('endpoint', $validated['endpoint'])->delete();

        return response()->json(['message' => 'Đã hủy nhận thông báo trên thiết bị này.']);
    }
}
