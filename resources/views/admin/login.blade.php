@extends('layouts.nfc')
@section('title', 'Đăng nhập quản trị | The HBG')
@section('body')
<div class="container" style="max-width:450px;margin-top:8vh">
 <div class="panel"><div class="brand" style="color:#224086">THE HBG</div><h2>Đăng nhập quản trị</h2>
 <p class="muted">Hệ thống hồ sơ thẻ nhân viên NFC</p>
 <form method="post" action="{{ route('admin.login') }}">@csrf
  <div class="field"><label>Email</label><input class="input" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required></div>
  <div class="field"><label>Mật khẩu</label><input class="input" type="password" name="password" autocomplete="current-password" required></div>
  @if($errors->any())<p class="error">{{ $errors->first() }}</p>@endif
  <button class="btn" style="width:100%">Đăng nhập</button>
 </form></div></div>
@endsection
