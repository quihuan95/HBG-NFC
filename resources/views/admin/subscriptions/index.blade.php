@extends('layouts.nfc')
@section('title', 'Thiết bị nhận thông báo | The HBG NFC')
@section('body')
<header class="top"><div class="brand">THE HBG / NFC ADMIN</div><nav class="top-nav"><a href="{{ route('admin.employees.index') }}">Nhân viên</a><a href="{{ route('admin.notifications.index') }}">Thông báo</a><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn secondary">Đăng xuất</button></form></nav></header>
<div class="container"><h1>Thiết bị nhận thông báo</h1><p class="muted">Mỗi dòng tương ứng một hồ sơ trình duyệt đã bật thông báo.</p>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
<div class="panel"><table class="mobile-cards"><thead><tr><th>ID</th><th>Endpoint</th><th>Đăng ký/cập nhật</th><th>Thao tác</th></tr></thead><tbody>
@forelse($subscriptions as $subscription)<tr><td class="mobile-primary"><strong>Thiết bị #{{ $subscription->id }}</strong></td><td data-label="Endpoint" class="break" style="max-width:620px">{{ $subscription->endpoint }}</td><td data-label="Cập nhật">{{ $subscription->updated_at->format('d/m/Y H:i') }}</td><td class="mobile-actions"><form method="post" action="{{ route('admin.subscriptions.destroy', $subscription) }}" onsubmit="return confirm('Xóa thiết bị này?')">@csrf @method('DELETE')<button class="btn danger">Xóa thiết bị</button></form></td></tr>
@empty<tr><td colspan="4">Chưa có thiết bị nào đăng ký.</td></tr>@endforelse
</tbody></table><div style="margin-top:20px">{{ $subscriptions->links() }}</div></div></div>
@endsection
