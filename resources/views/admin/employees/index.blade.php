@extends('layouts.nfc')
@section('title', 'Nhân viên | The HBG NFC')
@section('body')
<div style="position:fixed;right:190px;top:18px;z-index:10"><a class="btn gold" href="{{ route('admin.notifications.index') }}">Thông báo</a></div>
<header class="top"><div style="display:flex;align-items:center;gap:12px"><div style="background:#fff;padding:5px 12px;border-radius:8px;display:flex;align-items:center"><img src="{{ asset('images/logo.png') }}" alt="THE HBG" style="height:24px;display:block"></div><span style="font-weight:700;font-size:13px;letter-spacing:1px;color:#fcebc2">/ NFC ADMIN</span></div><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn secondary">Đăng xuất</button></form></header>
<div class="container"><div class="actions" style="justify-content:space-between;margin-bottom:18px"><div><h1 style="margin:0 0 5px">Danh sách nhân viên</h1><span class="muted">Quản lý hồ sơ và URL gắn lên thẻ NFC</span></div><a class="btn gold" href="{{ route('admin.employees.create') }}">+ Thêm nhân viên</a></div>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
<div class="panel"><table><thead><tr><th>Mã</th><th>Nhân viên</th><th>Chức vụ</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>
@forelse($employees as $employee)
<tr><td><strong>{{ $employee->code }}</strong></td><td>{{ $employee->name }}</td><td>{{ $employee->job_title ?: '—' }}</td><td>{{ $employee->active ? 'Hoạt động' : 'Tạm ẩn' }}</td><td><div class="actions"><a href="{{ route('admin.employees.edit', $employee) }}">Sửa</a><a href="{{ route('card.show', $employee->code) }}" target="_blank" rel="noopener">Xem thẻ</a></div></td></tr>
@empty<tr><td colspan="5">Chưa có nhân viên nào.</td></tr>@endforelse
</tbody></table><div style="margin-top:20px">{{ $employees->links() }}</div></div></div>
@endsection
