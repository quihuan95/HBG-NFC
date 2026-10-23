@extends('layouts.nfc')
@section('title', 'Nhân viên | The HBG NFC')
@section('body')
<header class="top"><div style="display:flex;align-items:center;gap:12px"><div style="background:#fff;padding:5px 12px;border-radius:8px;display:flex;align-items:center"><img src="{{ asset('images/logo.png') }}" alt="THE HBG" style="height:24px;display:block"></div><span style="font-weight:700;font-size:13px;letter-spacing:1px;color:#fcebc2">/ NFC ADMIN</span></div><nav class="top-nav"><a href="{{ route('admin.notifications.index') }}">Thông báo</a><a href="{{ route('admin.subscriptions.index') }}">Thiết bị</a><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn secondary">Đăng xuất</button></form></nav></header>

<div class="container">
    <div class="page-heading">
        <div>
            <h1>Danh sách nhân viên</h1>
            <span class="muted">Quản lý hồ sơ và URL gắn lên thẻ NFC</span>
        </div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <a class="btn secondary" href="{{ route('admin.employees.export_qr_zip') }}" title="Tải xuống tất cả mã QR dưới dạng tệp ZIP">
                ⬇ Xuất toàn bộ QR (ZIP)
            </a>
            <a class="btn gold" href="{{ route('admin.employees.create') }}">+ Thêm nhân viên</a>
        </div>
    </div>

    @if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert" style="background:#fde8e8;color:#9b1c1c">{{ session('error') }}</div>@endif

    <div class="panel">
        <div id="selectionBar" style="display:none;background:#eef4ff;border:1px solid #c7d9f9;padding:12px 18px;border-radius:12px;margin-bottom:16px;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
            <div style="display:flex;align-items:center;gap:10px">
                <span style="font-weight:600;font-size:14px;color:#224086">Đang chọn: <strong id="selectedCount">0</strong> nhân viên</span>
                <button type="button" class="btn secondary" style="min-height:30px;padding:3px 10px;font-size:12px" onclick="clearSelection()">Bỏ chọn</button>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <button type="button" class="btn gold" style="min-height:36px;padding:6px 14px;font-size:13px" onclick="submitBulkExport()">
                    ⬇ Xuất QR đã chọn (ZIP)
                </button>
                <button type="button" class="btn danger" style="min-height:36px;padding:6px 14px;font-size:13px" onclick="submitBulkDelete()">
                    🗑 Xóa nhân viên đã chọn
                </button>
            </div>
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
                        <form method="post" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên {{ $employee->name }} ({{ $employee->code }})?')" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" type="submit">Xóa</button>
                        </form>
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

<form id="bulkActionForm" method="post" style="display:none">
    @csrf
    <input type="hidden" name="_method" id="bulkMethod" value="POST">
    <div id="bulkInputsContainer"></div>
</form>

<script>
function getSelectedIds() {
    return Array.from(document.querySelectorAll('.employee-cb:checked')).map(cb => cb.value);
}

function updateSelectionState() {
    const checkboxes = document.querySelectorAll('.employee-cb');
    const checked = document.querySelectorAll('.employee-cb:checked');
    const selectAll = document.getElementById('selectAll');
    const count = checked.length;
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

function submitBulkExport() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Vui lòng chọn ít nhất một nhân viên.');
        return;
    }
    const form = document.getElementById('bulkActionForm');
    form.action = "{{ route('admin.employees.export_qr_zip') }}";
    document.getElementById('bulkMethod').value = 'POST';
    const container = document.getElementById('bulkInputsContainer');
    container.innerHTML = '';
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        container.appendChild(input);
    });
    form.submit();
}

function submitBulkDelete() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Vui lòng chọn ít nhất một nhân viên.');
        return;
    }
    if (!confirm(`Bạn có chắc chắn muốn xóa ${ids.length} nhân viên đã chọn?\nHành động này không thể hoàn tác!`)) {
        return;
    }
    const form = document.getElementById('bulkActionForm');
    form.action = "{{ route('admin.employees.bulk_destroy') }}";
    document.getElementById('bulkMethod').value = 'DELETE';
    const container = document.getElementById('bulkInputsContainer');
    container.innerHTML = '';
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        container.appendChild(input);
    });
    form.submit();
}
</script>
@endsection
