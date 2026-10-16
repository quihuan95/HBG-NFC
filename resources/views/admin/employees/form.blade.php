@extends('layouts.nfc')
@section('title', ($employee->exists ? 'Sửa' : 'Thêm').' nhân viên | The HBG')
@section('body')
<header class="top"><div class="brand">THE HBG <span style="font-weight:400;font-size:12px">/ NFC ADMIN</span></div><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn secondary">Đăng xuất</button></form></header>
<div class="container"><a href="{{ route('admin.employees.index') }}">← Danh sách nhân viên</a><h1>{{ $employee->exists ? 'Cập nhật hồ sơ' : 'Tạo hồ sơ nhân viên' }}</h1>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
@if($errors->any())<div class="error" style="margin-bottom:18px">Có lỗi dữ liệu. Kiểm tra các trường bên dưới.</div>@endif
<div class="panel">
@if($employee->exists)
<div style="margin-bottom:25px;padding:14px;background:#eef3ff;border-radius:10px"><strong>URL để ghi vào NFC:</strong><div class="break"><a href="{{ route('card.show', $employee->code) }}" target="_blank" rel="noopener">{{ route('card.show', $employee->code) }}</a></div><p class="muted">Chép URL trên vào NFC Tools → Write → URL/URI. Mã nhân viên được khóa để giữ nguyên link.</p></div>
@endif
<form method="post" enctype="multipart/form-data" action="{{ $employee->exists ? route('admin.employees.update', $employee) : route('admin.employees.store') }}">@csrf @if($employee->exists)@method('PUT')@endif
<div class="grid">
<div class="field"><label>Mã nhân viên *</label><input class="input" name="code" value="{{ old('code', $employee->code) }}" pattern="[A-Za-z0-9_-]{2,32}" maxlength="32" {{ $employee->exists ? 'disabled' : 'required' }} placeholder="HBG001"><div class="muted">Dùng để tạo URL /HBG001. Không đổi sau khi cấp thẻ.</div>@error('code')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Họ và tên *</label><input class="input" name="name" value="{{ old('name', $employee->name) }}" maxlength="150" required>@error('name')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Chức vụ</label><input class="input" name="job_title" value="{{ old('job_title', $employee->job_title) }}">@error('job_title')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Phòng ban</label><input class="input" name="department" value="{{ old('department', $employee->department) }}">@error('department')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Email công việc</label><input class="input" type="email" name="email" value="{{ old('email', $employee->email) }}">@error('email')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Số điện thoại công việc</label><input class="input" name="phone" value="{{ old('phone', $employee->phone) }}">@error('phone')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Zalo (số điện thoại hoặc link)</label><input class="input" name="zalo" value="{{ old('zalo', $employee->zalo) }}">@error('zalo')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Website (https://...)</label><input class="input" type="url" name="website" value="{{ old('website', $employee->website) }}">@error('website')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Ảnh chân dung (jpg/png/webp, ≤ 2MB)</label><input class="input" type="file" accept="image/jpeg,image/png,image/webp" name="photo">@error('photo')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Trạng thái</label><label style="font-weight:400"><input type="checkbox" name="active" value="1" {{ old('active', $employee->exists ? $employee->active : true) ? 'checked' : '' }}> Hiển thị hồ sơ công khai</label></div>
</div>
<div class="field"><label>Giới thiệu ngắn</label><textarea class="input" rows="4" name="bio" maxlength="1500">{{ old('bio', $employee->bio) }}</textarea>@error('bio')<div class="error">{{ $message }}</div>@enderror</div>
<div class="actions"><button class="btn">{{ $employee->exists ? 'Lưu thay đổi' : 'Tạo nhân viên' }}</button><a class="btn secondary" href="{{ route('admin.employees.index') }}">Hủy</a></div>
</form></div></div>
@endsection
