<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MinhCardRequest;
use App\Models\MinhCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MinhCardController extends Controller
{
    public function index(): View
    {
        $cards = MinhCard::query()->orderByDesc('id')->paginate(15);

        return view('admin.minh_cards.index', compact('cards'));
    }

    public function create(): View
    {
        return view('admin.minh_cards.form', ['card' => new MinhCard]);
    }

    public function store(MinhCardRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['photo']);
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('minh-card-photos', 'public');
        }
        $card = MinhCard::create($data);

        return redirect()->route('admin.minh.edit', $card)->with('success', 'Đã tạo thẻ cá nhân và URL: /minh/'.$card->code);
    }

    public function edit(MinhCard $card): View
    {
        return view('admin.minh_cards.form', ['card' => $card]);
    }

    public function update(MinhCardRequest $request, MinhCard $card): RedirectResponse
    {
        $data = $request->safe()->except(['photo']);
        if ($request->hasFile('photo')) {
            $newPath = $request->file('photo')->store('minh-card-photos', 'public');
            if ($card->photo_path) {
                Storage::disk('public')->delete($card->photo_path);
            }
            $data['photo_path'] = $newPath;
        }
        $card->update($data);

        return back()->with('success', 'Đã cập nhật thông tin thẻ. Link NFC /minh/'.$card->code.' được giữ nguyên.');
    }

    public function destroy(MinhCard $card): RedirectResponse
    {
        if ($card->photo_path) {
            Storage::disk('public')->delete($card->photo_path);
        }
        $card->delete();

        return redirect()->route('admin.minh.index')->with('success', 'Đã xóa thẻ cá nhân.');
    }
}
