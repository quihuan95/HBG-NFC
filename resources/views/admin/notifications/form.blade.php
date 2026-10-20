@extends('layouts.nfc')
@section('title', ($pushMessage->exists ? 'Sửa' : 'Soạn').' thông báo | The HBG NFC')
@section('body')
<header class="top"><div class="brand">THE HBG / NFC ADMIN</div><div class="actions"><a href="{{ route('admin.employees.index') }}">Nhân viên</a><a href="{{ route('admin.notifications.index') }}">Thông báo</a></div></header>
<div class="container"><a href="{{ route('admin.notifications.index') }}">← Danh sách thông báo</a><h1>{{ $pushMessage->exists ? 'Cập nhật thông báo' : 'Soạn thông báo mới' }}</h1>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
@if($errors->any())<div class="error" style="margin-bottom:16px">{{ $errors->first() }}</div>@endif
<div class="panel"><form method="post" action="{{ $pushMessage->exists ? route('admin.notifications.update', $pushMessage) : route('admin.notifications.store') }}">@csrf @if($pushMessage->exists)@method('PUT')@endif
<div class="field"><label>Tiêu đề *</label><input class="input" name="title" maxlength="120" required value="{{ old('title', $pushMessage->title) }}">@error('title')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>Nội dung *</label><textarea class="input" name="body" rows="5" maxlength="1000" required>{{ old('body', $pushMessage->body) }}</textarea>@error('body')<div class="error">{{ $message }}</div>@enderror</div>
<div class="field"><label>URL mở khi nhấn thông báo</label><input class="input" type="url" name="target_url" maxlength="2048" value="{{ old('target_url', $pushMessage->target_url) }}" placeholder="{{ url('/') }}"><div class="muted">Để trống để mở trang chủ.</div>@error('target_url')<div class="error">{{ $message }}</div>@enderror</div>
<div class="actions"><button class="btn">Lưu bản nháp</button><a class="btn secondary" href="{{ route('admin.notifications.index') }}">Hủy</a></div></form>
@if($pushMessage->exists)<form method="post" action="{{ route('admin.notifications.send', $pushMessage) }}" style="margin-top:18px;padding-top:18px;border-top:1px solid #e8edf5" onsubmit="return confirm('Gửi tới tất cả thiết bị đang đăng ký?')">@csrf<button class="btn gold">Gửi{{ $pushMessage->sent_at ? ' lại' : '' }} tới tất cả thiết bị</button></form>@endif
</div></div>
@endsection
