<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NfcCardController extends Controller
{
    public function show(Request $request, string $code): View|Response
    {
        $employee = Employee::query()->where('code', strtoupper($code))->where('active', true)->firstOrFail();
        if ($request->query('download') === 'vcard') {
            $clean = fn (?string $value) => str_replace(['\\', "\r", "\n", ';', ','], ['\\\\', '', '\\n', '\\;', '\\,'], $value ?? '');
            $lines = ['BEGIN:VCARD', 'VERSION:3.0', 'FN:'.$clean($employee->name), 'ORG:HoaBinh Group'];
            if ($employee->job_title) {
                $lines[] = 'TITLE:'.$clean($employee->job_title);
            }
            if ($employee->phone) {
                $lines[] = 'TEL;TYPE=WORK:'.$clean($employee->phone);
            }
            if ($employee->email) {
                $lines[] = 'EMAIL;TYPE=WORK:'.$clean($employee->email);
            }
            if ($employee->website) {
                $lines[] = 'URL:'.$clean($employee->website);
            }
            if ($employee->facebook_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=facebook:'.$clean($employee->facebook_url);
            }
            if ($employee->linkedin_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=linkedin:'.$clean($employee->linkedin_url);
            }
            if ($employee->whatsapp_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=whatsapp:'.$clean($employee->whatsapp_url);
            }
            if ($employee->viber_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=viber:'.$clean($employee->viber_url);
            }
            $lines[] = 'END:VCARD';

            return response(implode("\r\n", $lines)."\r\n", 200, [
                'Content-Type' => 'text/vcard; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="'.$employee->code.'.vcf"',
            ]);
        }

        return view('card.show', compact('employee'));
    }
}
