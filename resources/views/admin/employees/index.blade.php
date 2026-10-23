@extends('layouts.nfc')
@section('title', 'Nhân viên | The HBG NFC')
@section('body')
<header class="top"><div style="display:flex;align-items:center;gap:12px"><div style="background:#fff;padding:5px 12px;border-radius:8px;display:flex;align-items:center"><img src="{{ asset('images/logo.png') }}" alt="THE HBG" style="height:24px;display:block"></div><span style="font-weight:700;font-size:13px;letter-spacing:1px;color:#fcebc2">/ NFC ADMIN</span></div><nav class="top-nav"><a href="{{ route('admin.notifications.index') }}">Thông báo</a><a href="{{ route('admin.subscriptions.index') }}">Thiết bị</a><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn secondary">Đăng xuất</button></form></nav></header>

<form id="bulkExportForm" method="post" action="{{ route('admin.employees.export_qr_zip') }}">
@csrf
<div class="container">
    <div class="page-heading">
        <div>
            <h1>Danh sách nhân viên</h1>
            <span class="muted">Quản lý hồ sơ và URL gắn lên thẻ NFC</span>
        </div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <button type="submit" class="btn secondary" id="btnExportZip" title="Tải xuống mã QR dưới dạng tệp ZIP">
                ⬇ Xuất toàn bộ QR (ZIP)
            </button>
            <a class="btn gold" href="{{ route('admin.employees.create') }}">+ Thêm nhân viên</a>
        </div>
    </div>

    @if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert" style="background:#fde8e8;color:#9b1c1c">{{ session('error') }}</div>@endif

    <div class="panel">
        <div id="selectionBar" style="display:none;background:#eef4ff;border:1px solid #c7d9f9;padding:10px 16px;border-radius:10px;margin-bottom:14px;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
            <span style="font-weight:600;font-size:14px;color:#224086">Đang chọn: <strong id="selectedCount">0</strong> nhân viên</span>
            <button type="button" class="btn secondary" style="min-height:32px;padding:4px 12px;font-size:13px" onclick="clearSelection()">Bỏ chọn tất cả</button>
        </div>

        <table class="mobile-cards">
            <thead>
                <tr>
                    <th style="width:38px;text-align:center"><input type="checkbox" id="selectAll" style="width:16px;height:16px;cursor:pointer" title="Chọn tất cả"></th>
                    <th>Mã</th>
                    <th>Nhân viên</th>
                    <th>Chức vụ</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            @forelse($employees as $employee)
            <tr>
                <td style="text-align:center" data-label="Chọn">
                    <input type="checkbox" name="ids[]" value="{{ $employee->id }}" class="employee-cb" style="width:16px;height:16px;cursor:pointer" onchange="updateSelectionState()">
                </td>
                <td class="mobile-primary">
                    <span class="desktop-only"><strong>{{ $employee->code }}</strong></span>
                    <span class="mobile-only"><strong>{{ $employee->name }}</strong><span class="muted">{{ $employee->code }}</span></span>
                </td>
                <td data-label="Nhân viên">{{ $employee->name }}</td>
                <td data-label="Chức vụ">{{ $employee->job_title ?: '—' }}</td>
                <td data-label="Trạng thái">{{ $employee->active ? 'Hoạt động' : 'Tạm ẩn' }}</td>
                <td class="mobile-actions">
                    <div class="actions">
                        <a class="btn secondary" href="{{ route('admin.employees.download_qr', $employee) }}" title="Tải ảnh mã QR">QR</a>
                        <a class="btn secondary" href="{{ route('admin.employees.edit', $employee) }}">Sửa</a>
                        <a class="btn" href="{{ route('card.show', $employee->code) }}" target="_blank" rel="noopener">Xem thẻ</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6">Chưa có nhân viên nào.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="margin-top:20px">{{ $employees->links() }}</div>
    </div>
</div>
</form>

<script>
function updateSelectionState() {
    const checkboxes = document.querySelectorAll('.employee-cb');
    const checked = document.querySelectorAll('.employee-cb:checked');
    const selectAll = document.getElementById('selectAll');
    const count = checked.length;
    const btn = document.getElementById('btnExportZip');
    const bar = document.getElementById('selectionBar');
    const countSpan = document.getElementById('selectedCount');

    if (countSpan) countSpan.textContent = count;

    if (selectAll) {
        selectAll.checked = checkboxes.length > 0 && count === checkboxes.length;
        selectAll.indeterminate = count > 0 && count < checkboxes.length;
    }

    if (bar) {
        bar.style.display = count > 0 ? 'flex' : 'none';
    }

    if (btn) {
        if (count > 0) {
            btn.innerHTML = `⬇ Xuất QR đã chọn (ZIP) (${count})`;
            btn.classList.remove('secondary');
            btn.classList.add('gold');
        } else {
            btn.innerHTML = `⬇ Xuất toàn bộ QR (ZIP)`;
            btn.classList.remove('gold');
            btn.classList.add('secondary');
        }
    }
}

document.getElementById('selectAll')?.addEventListener('change', function() {
    const isChecked = this.checked;
    document.querySelectorAll('.employee-cb').forEach(cb => {
        cb.checked = isChecked;
    });
    updateSelectionState();
});

function clearSelection() {
    document.querySelectorAll('.employee-cb').forEach(cb => cb.checked = false);
    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
    updateSelectionState();
}
</script>
@endsection
