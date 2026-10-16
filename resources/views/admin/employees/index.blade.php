@extends('layouts.nfc')
@section('title', 'Nhân viên | The HBG NFC')
@section('body')
<header class="top"><div class="brand">THE HBG <span style="font-weight:400;font-size:12px">/ NFC ADMIN</span></div><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn secondary">Đăng xuất</button></form></header>
<div class="container"><div class="actions" style="justify-content:space-between;margin-bottom:18px"><div><h1 style="margin:0 0 5px">Danh sách nhân viên</h1><span class="muted">Quản lý hồ sơ và URL gắn lên thẻ NFC</span></div><a class="btn gold" href="{{ route('admin.employees.create') }}">+ Thêm nhân viên</a></div>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
<div class="panel"><table><thead><tr><th>Mã</th><th>Nhân viên</th><th>Chức vụ</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>
@forelse($employees as $employee)
<tr><td><strong>{{ $employee->code }}</strong></td><td>{{ $employee->name }}</td><td>{{ $employee->job_title ?: '—' }}</td><td>{{ $employee->active ? 'Hoạt động' : 'Tạm ẩn' }}</td><td><div class="actions"><a href="{{ route('admin.employees.edit', $employee) }}">Sửa</a><a href="{{ route('card.show', $employee->code) }}" target="_blank" rel="noopener">Xem thẻ</a></div></td></tr>
@empty<tr><td colspan="5">Chưa có nhân viên nào.</td></tr>@endforelse
</tbody></table><div style="margin-top:20px">{{ $employees->links() }}</div></div></div>
@endsection
