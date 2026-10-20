@extends('layouts.nfc')
@section('title', 'Quản trị Thẻ Cá Nhân | /admin/minh')
@section('body')
<div style="position:fixed;right:190px;top:18px;z-index:10"><a class="btn gold" href="{{ route('admin.notifications.index') }}">Thông báo</a></div>
<header class="top" style="background:#0f172a;border-bottom:1px solid rgba(255,255,255,0.1)">
    <div style="display:flex;align-items:center;gap:12px">
        <div style="background:linear-gradient(135deg, #2563eb, #3b82f6);color:#fff;padding:5px 12px;border-radius:8px;font-weight:800;font-size:14px;letter-spacing:1px">
            MINH
        </div>
        <span style="font-weight:700;font-size:13px;letter-spacing:1px;color:#93c5fd">/ NFC PRIVATE ADMIN</span>
        <span style="font-size:11px;background:rgba(255,255,255,0.1);padding:3px 8px;border-radius:999px;color:#cbd5e1">/admin/minh</span>
    </div>
    <div style="display:flex;align-items:center;gap:14px">
        <a href="{{ route('admin.employees.index') }}" style="color:#94a3b8;font-size:13px;text-decoration:none">🏢 Về quản trị HBG</a>
        <form method="post" action="{{ route('admin.minh.logout') }}">@csrf<button class="btn secondary" style="padding:7px 14px;font-size:13px">Đăng xuất</button></form>
    </div>
</header>

<div class="container">
    <div class="actions" style="justify-content:space-between;margin-bottom:18px">
        <div>
            <h1 style="margin:0 0 5px">Quản lý Thẻ Cá Nhân (Private)</h1>
            <span class="muted">Hệ thống danh thiếp riêng biệt · Đường dẫn công khai độc lập: <strong>/minh/{mã}</strong></span>
        </div>
        <a class="btn gold" href="{{ route('admin.minh.create') }}">+ Tạo thẻ cá nhân mới</a>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <table>
            <thead>
                <tr>
                    <th>Mã thẻ (Public URL)</th>
                    <th>Chủ thẻ</th>
                    <th>Chức danh / Đơn vị</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cards as $card)
                    <tr>
                        <td>
                            <strong style="color:#2563eb;font-size:14px">/minh/{{ $card->code }}</strong>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                @if($card->photo_path)
                                    <img src="{{ asset('storage/'.$card->photo_path) }}" alt="{{ $card->name }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:1px solid #e2e8f0">
                                @else
                                    <div style="width:38px;height:38px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:#475569">
                                        {{ mb_substr($card->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <span style="font-weight:600">{{ $card->name }}</span>
                                    @if($card->email)
                                        <div style="font-size:12px;color:#64748b">{{ $card->email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $card->job_title ?: '—' }}</div>
                            @if($card->company)
                                <div style="font-size:12px;color:#64748b">{{ $card->company }}</div>
                            @endif
                        </td>
                        <td>
                            @if($card->active)
                                <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;background:#ecfdf5;color:#059669">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#10b981"></span> Hoạt động
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:600;background:#fef2f2;color:#dc2626">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#ef4444"></span> Tạm ẩn
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.minh.edit', $card) }}">Sửa</a>
                                <a href="{{ route('minh.card.show', $card->code) }}" target="_blank" rel="noopener" style="color:#059669;font-weight:600">Xem thẻ</a>
                                <form method="post" action="{{ route('admin.minh.destroy', $card) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thẻ này?')" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#b52436;cursor:pointer;padding:0;font:inherit;text-decoration:underline">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:36px 10px;color:#64748b">
                            Chưa có thẻ cá nhân nào tại <strong>/admin/minh</strong>. Nhấn <strong>+ Tạo thẻ cá nhân mới</strong> để bắt đầu!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($cards->hasPages())
            <div style="margin-top:20px">{{ $cards->links() }}</div>
        @endif
    </div>
</div>
@endsection
