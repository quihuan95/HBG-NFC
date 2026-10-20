@extends('layouts.nfc')
@section('title', ($card->exists ? 'Sửa' : 'Thêm').' thẻ cá nhân | /admin/minh')
@section('body')
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
    <a href="{{ route('admin.minh.index') }}">← Quay lại danh sách thẻ cá nhân (/admin/minh)</a>
    <h1 style="margin-top:10px">{{ $card->exists ? 'Cập nhật thẻ cá nhân' : 'Tạo thẻ cá nhân mới' }}</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error" style="margin-bottom:18px">Có lỗi dữ liệu. Vui lòng kiểm tra các trường bên dưới.</div>
    @endif

    <div class="panel">
        @if($card->exists)
            <div style="margin-bottom:25px;padding:16px;background:#f0f7ff;border:1px solid #bfdbfe;border-radius:12px">
                <strong style="color:#1e40af">URL định tuyến NFC cá nhân:</strong>
                <div class="break" style="margin-top:6px">
                    <a href="{{ route('minh.card.show', $card->code) }}" target="_blank" rel="noopener" style="font-weight:700;font-size:16px;color:#2563eb">
                        {{ route('minh.card.show', $card->code) }}
                    </a>
                </div>
                <p class="muted" style="margin:6px 0 0">
                    Chép URL này vào ứng dụng NFC Tools → Write → Add a record → URL/URI để nạp vào chip thẻ NFC.
                </p>
            </div>
        @endif

        <form method="post" enctype="multipart/form-data" action="{{ $card->exists ? route('admin.minh.update', $card) : route('admin.minh.store') }}">
            @csrf
            @if($card->exists)
                @method('PUT')
            @endif

            <div class="grid">
                <div class="field">
                    <label>Mã thẻ (URL slug) *</label>
                    <input class="input" name="code" value="{{ old('code', $card->code) }}" pattern="[A-Za-z0-9_-]{2,32}" maxlength="32" {{ $card->exists ? 'disabled' : 'required' }} placeholder="VD: minh, card1, vip">
                    <div class="muted">Tạo URL công khai: /minh/<strong>mã_thẻ</strong>. Mã được khóa sau khi tạo để tránh đứt liên kết NFC.</div>
                    @error('code')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Họ và tên chủ thẻ *</label>
                    <input class="input" name="name" value="{{ old('name', $card->name) }}" maxlength="150" required placeholder="Phạm Quang Minh">
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Chức danh / Nghề nghiệp</label>
                    <input class="input" name="job_title" value="{{ old('job_title', $card->job_title) }}" placeholder="Full-Stack Developer / Freelancer">
                    @error('job_title')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Tổ chức / Thương hiệu / Công ty</label>
                    <input class="input" name="company" value="{{ old('company', $card->company) }}" placeholder="Tech & Creative / HBG">
                    @error('company')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <img src="{{ asset('storage/icon/phone.png') }}" alt="Phone" style="width:18px;height:18px;object-fit:contain">
                        Số điện thoại liên hệ
                    </label>
                    <input class="input" name="phone" value="{{ old('phone', $card->phone) }}" placeholder="0901234567">
                    @error('phone')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <img src="{{ asset('storage/icon/email.png') }}" alt="Email" style="width:18px;height:18px;object-fit:contain">
                        Email cá nhân / công việc
                    </label>
                    <input class="input" type="email" name="email" value="{{ old('email', $card->email) }}" placeholder="minh@example.com">
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <img src="{{ asset('storage/icon/zalo.webp') }}" alt="Zalo" style="width:18px;height:18px;object-fit:contain">
                        Zalo (Số điện thoại hoặc link)
                    </label>
                    <input class="input" name="zalo" value="{{ old('zalo', $card->zalo) }}" placeholder="0901234567 hoặc https://zalo.me/...">
                    @error('zalo')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#0088cc"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                        Telegram (Username hoặc link t.me/...)
                    </label>
                    <input class="input" name="telegram" value="{{ old('telegram', $card->telegram) }}" placeholder="@username hoặc https://t.me/...">
                    @error('telegram')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <img src="{{ asset('storage/icon/facebook.webp') }}" alt="Facebook" style="width:18px;height:18px;object-fit:contain">
                        Facebook (Link cá nhân)
                    </label>
                    <input class="input" name="facebook" value="{{ old('facebook', $card->facebook) }}" placeholder="https://facebook.com/username">
                    @error('facebook')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#181717"><path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/></svg>
                        GitHub (Username hoặc link github.com/...)
                    </label>
                    <input class="input" name="github" value="{{ old('github', $card->github) }}" placeholder="https://github.com/username">
                    @error('github')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <img src="{{ asset('storage/icon/whatsapp.png') }}" alt="WhatsApp" style="width:18px;height:18px;object-fit:contain">
                        WhatsApp (Số điện thoại hoặc wa.me)
                    </label>
                    <input class="input" name="whatsapp" value="{{ old('whatsapp', $card->whatsapp) }}" placeholder="+84901234567 hoặc 0901234567">
                    @error('whatsapp')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <img src="{{ asset('storage/icon/viber.png') }}" alt="Viber" style="width:18px;height:18px;object-fit:contain">
                        Viber (Số điện thoại)
                    </label>
                    <input class="input" name="viber" value="{{ old('viber', $card->viber) }}" placeholder="0901234567 hoặc +84...">
                    @error('viber')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <img src="{{ asset('storage/icon/linkedIn.webp') }}" alt="LinkedIn" style="width:18px;height:18px;object-fit:contain">
                        LinkedIn (Link hoặc username)
                    </label>
                    <input class="input" name="linkedin" value="{{ old('linkedin', $card->linkedin) }}" placeholder="https://linkedin.com/in/username">
                    @error('linkedin')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:6px">
                        <img src="{{ asset('storage/icon/web.png') }}" alt="Website" style="width:18px;height:18px;object-fit:contain">
                        Website cá nhân / Portfolio
                    </label>
                    <input class="input" type="url" name="website" value="{{ old('website', $card->website) }}" placeholder="https://yourdomain.com">
                    @error('website')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Ảnh chân dung / Avatar (jpg/png/webp, ≤ 2MB)</label>
                    <input class="input" type="file" accept="image/jpeg,image/png,image/webp" name="photo">
                    @if($card->photo_path)
                        <div style="margin-top:8px;display:flex;align-items:center;gap:10px">
                            <img src="{{ asset('storage/'.$card->photo_path) }}" alt="Ảnh hiện tại" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0">
                            <span class="muted" style="font-size:12px">Ảnh đại diện hiện tại</span>
                        </div>
                    @endif
                    @error('photo')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Trạng thái</label>
                    <label style="font-weight:400;display:flex;align-items:center;gap:8px;margin-top:10px">
                        <input type="checkbox" name="active" value="1" {{ old('active', $card->exists ? $card->active : true) ? 'checked' : '' }}>
                        <span>Hiển thị thẻ công khai tại <strong>/minh/{{ $card->code ?: 'mã' }}</strong></span>
                    </label>
                </div>
            </div>

            <div class="field">
                <label>Giới thiệu bản thân / Bio</label>
                <textarea class="input" rows="4" name="bio" maxlength="1500" placeholder="Đôi nét giới thiệu về bạn, thế mạnh, dịch vụ hoặc thông điệp gửi gắm...">{{ old('bio', $card->bio) }}</textarea>
                @error('bio')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="actions" style="margin-top:20px">
                <button class="btn gold">{{ $card->exists ? 'Lưu thay đổi' : 'Tạo thẻ cá nhân' }}</button>
                <a class="btn secondary" href="{{ route('admin.minh.index') }}">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
