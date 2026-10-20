@extends('layouts.nfc')
@section('title', 'Nhân viên | The HBG NFC')
@section('body')
<header class="top"><div style="display:flex;align-items:center;gap:12px"><div style="background:#fff;padding:5px 12px;border-radius:8px;display:flex;align-items:center"><img src="{{ asset('images/logo.png') }}" alt="THE HBG" style="height:24px;display:block"></div><span style="font-weight:700;font-size:13px;letter-spacing:1px;color:#fcebc2">/ NFC ADMIN</span></div><nav class="top-nav"><a href="{{ route('admin.notifications.index') }}">Thông báo</a><a href="{{ route('admin.subscriptions.index') }}">Thiết bị</a><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn secondary">Đăng xuất</button></form></nav></header>
<div class="container"><div class="page-heading"><div><h1>Danh sách nhân viên</h1><span class="muted">Quản lý hồ sơ và URL gắn lên thẻ NFC</span></div><a class="btn gold" href="{{ route('admin.employees.create') }}">+ Thêm nhân viên</a></div>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
<div class="panel"><table class="mobile-cards"><thead><tr><th>Mã</th><th>Nhân viên</th><th>Chức vụ</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>
@forelse($employees as $employee)
<tr><td class="mobile-primary"><span class="desktop-only"><strong>{{ $employee->code }}</strong></span><span class="mobile-only"><strong>{{ $employee->name }}</strong><span class="muted">{{ $employee->code }}</span></span></td><td data-label="Nhân viên">{{ $employee->name }}</td><td data-label="Chức vụ">{{ $employee->job_title ?: '—' }}</td><td data-label="Trạng thái">{{ $employee->active ? 'Hoạt động' : 'Tạm ẩn' }}</td><td class="mobile-actions"><div class="actions"><a class="btn secondary" href="{{ route('admin.employees.edit', $employee) }}">Sửa</a><a class="btn" href="{{ route('card.show', $employee->code) }}" target="_blank" rel="noopener">Xem thẻ</a></div></td></tr>
@empty<tr><td colspan="5">Chưa có nhân viên nào.</td></tr>@endforelse
</tbody></table><div style="margin-top:20px">{{ $employees->links() }}</div></div></div>
@endsection
