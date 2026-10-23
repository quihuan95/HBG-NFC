<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::query()->orderByDesc('id')->paginate(15);

        return view('admin.employees.index', compact('employees'));
    }

    public function exportQrZip(\Illuminate\Http\Request $request, \App\Services\QrCodeService $qrService): \Symfony\Component\HttpFoundation\Response
    {
        $query = Employee::query()->orderBy('id');

        if ($request->filled('ids')) {
            $ids = is_array($request->input('ids'))
                ? $request->input('ids')
                : explode(',', (string) $request->input('ids'));
            $query->whereIn('id', array_filter($ids));
        } else {
            $query->where('active', true);
        }

        $employees = $query->get();

        if ($employees->isEmpty()) {
            return back()->with('error', 'Không tìm thấy nhân viên nào để xuất mã QR.');
        }

        $zipPath = $qrService->exportEmployeesToZip($employees);

        return response()->download($zipPath, 'HBG_NFC_QRCodes_' . date('Ymd_His') . '.zip')->deleteFileAfterSend(true);
    }

    public function downloadQr(Employee $employee, \App\Services\QrCodeService $qrService): \Illuminate\Http\Response
    {
        $content = $qrService->generateForEmployee($employee, 'png', 500);
        $fileName = $qrService->getEmployeeFileName($employee, 'png');

        return response($content, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function create(): View
    {
        return view('admin.employees.form', ['employee' => new Employee]);
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['photo']);
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('employee-photos', 'public');
        }
        $employee = Employee::create($data);

        return redirect()->route('admin.employees.edit', $employee)->with('success', 'Đã tạo hồ sơ và URL cho thẻ NFC.');
    }

    public function edit(Employee $employee): View
    {
        return view('admin.employees.form', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->safe()->except(['photo']);
        if ($request->hasFile('photo')) {
            $newPath = $request->file('photo')->store('employee-photos', 'public');
            if ($employee->photo_path) {
                Storage::disk('public')->delete($employee->photo_path);
            }
            $data['photo_path'] = $newPath;
        }
        $employee->update($data);

        return back()->with('success', 'Đã cập nhật. Link NFC vẫn giữ nguyên.');
    }
}
