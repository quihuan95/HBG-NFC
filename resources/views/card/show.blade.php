@extends('layouts.nfc')
@section('title', $employee->name.' | The HBG')
@section('body')
<div class="container"><main class="panel profile"><div class="hero"><div class="brand" style="color:#fff">THE HBG</div><p class="tag">EMPLOYEE DIGITAL CARD</p>
@if($employee->photo_path)<img class="cardphoto" src="{{ asset('storage/'.$employee->photo_path) }}" alt="Ảnh {{ $employee->name }}">@else<div class="cardphoto placeholder" style="margin:auto">{{ mb_strtoupper(mb_substr($employee->name, 0, 1)) }}</div>@endif
<h1>{{ $employee->name }}</h1><p style="margin:4px 0">{{ $employee->job_title ?: 'Nhân viên' }}</p><small style="color:#d9a65b">{{ $employee->department }}</small></div>
<div class="details">
@if($employee->email)<div class="detail"><small>Email công việc</small><span><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></span></div>@endif
@if($employee->phone)<div class="detail"><small>Điện thoại</small><span><a href="tel:{{ preg_replace('/[^+0-9]/', '', $employee->phone) }}">{{ $employee->phone }}</a></span></div>@endif
@if($employee->zalo)<div class="detail"><small>Zalo</small><span>{{ $employee->zalo }}</span></div>@endif
@if($employee->website)<div class="detail"><small>Website</small><span><a href="{{ $employee->website }}" rel="noopener noreferrer" target="_blank">{{ $employee->website }}</a></span></div>@endif
@if($employee->bio)<div class="detail"><small>Giới thiệu</small><span style="white-space:pre-line">{{ $employee->bio }}</span></div>@endif
<p style="text-align:center;margin:22px 0 0"><a class="btn gold" href="{{ route('card.show', $employee->code) }}?download=vcard" id="download-card">Lưu liên hệ vào danh bạ</a></p>
</div><div class="profile-footer">THE HBG · NFC Digital Business Card</div></main></div>
@endsection
