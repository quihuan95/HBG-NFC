@extends('layouts.nfc')
@section('title', 'Thông báo | The HBG NFC')
@section('body')
<header class="top"><div class="brand">THE HBG / NFC ADMIN</div><nav class="top-nav"><a href="{{ route('admin.employees.index') }}">Nhân viên</a><a href="{{ route('admin.subscriptions.index') }}">Thiết bị ({{ $subscriptionCount }})</a><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn secondary">Đăng xuất</button></form></nav></header>
<div class="container">
<div class="page-heading"><div><h1>Thông báo Web Push</h1><span class="muted">Soạn và gửi thông báo tới {{ $subscriptionCount }} thiết bị đang đăng ký</span></div><a class="btn gold" href="{{ route('admin.notifications.create') }}">+ Soạn thông báo</a></div>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
@if($errors->any())<div class="error" style="margin-bottom:16px">{{ $errors->first() }}</div>@endif
<div class="panel"><table class="mobile-cards"><thead><tr><th>Thông báo</th><th>Trạng thái</th><th>Lần gửi gần nhất</th><th>Kết quả</th><th>Thao tác</th></tr></thead><tbody>
@forelse($messages as $message)
<tr><td class="mobile-primary"><strong>{{ $message->title }}</strong><div class="muted">{{ str($message->body)->limit(80) }}</div></td><td data-label="Trạng thái">{{ $message->status === 'sent' ? 'Đã gửi' : 'Bản nháp' }}</td><td data-label="Lần gửi">{{ $message->sent_at?->format('d/m/Y H:i') ?: '—' }}</td><td data-label="Kết quả">Thành công: {{ $message->sent_count }} · Lỗi: {{ $message->failed_count }} · Hết hạn: {{ $message->expired_count }}</td><td class="mobile-actions"><div class="actions"><a class="btn secondary" href="{{ route('admin.notifications.edit', $message) }}">Sửa</a><form method="post" action="{{ route('admin.notifications.send', $message) }}" onsubmit="return confirm('Gửi thông báo này tới tất cả thiết bị?')">@csrf<button class="btn">Gửi{{ $message->sent_at ? ' lại' : '' }}</button></form><form method="post" action="{{ route('admin.notifications.destroy', $message) }}" onsubmit="return confirm('Xóa thông báo này?')">@csrf @method('DELETE')<button class="btn danger">Xóa</button></form></div></td></tr>
@empty<tr><td colspan="5">Chưa có thông báo nào.</td></tr>@endforelse
</tbody></table><div style="margin-top:20px">{{ $messages->links() }}</div></div></div>
@endsection
