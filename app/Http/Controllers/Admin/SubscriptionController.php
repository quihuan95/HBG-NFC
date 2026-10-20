<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        return view('admin.subscriptions.index', [
            'subscriptions' => PushSubscription::query()->latest()->paginate(20),
        ]);
    }

    public function destroy(PushSubscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return back()->with('success', 'Đã xóa thiết bị đăng ký.');
    }
}
