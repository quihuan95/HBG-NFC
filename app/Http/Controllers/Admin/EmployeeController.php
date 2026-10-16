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
