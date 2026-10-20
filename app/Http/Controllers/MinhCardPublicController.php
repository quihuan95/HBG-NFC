<?php

namespace App\Http\Controllers;

use App\Models\MinhCard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinhCardPublicController extends Controller
{
    public function show(Request $request, string $code): View|Response
    {
        $card = MinhCard::query()
            ->where('code', strtoupper($code))
            ->where('active', true)
            ->firstOrFail();

        if ($request->query('download') === 'vcard') {
            $clean = fn (?string $value) => str_replace(['\\', "\r", "\n", ';', ','], ['\\\\', '', '\\n', '\\;', '\\,'], $value ?? '');

            $lines = ['BEGIN:VCARD', 'VERSION:3.0', 'FN:'.$clean($card->name)];

            if ($card->company) {
                $lines[] = 'ORG:'.$clean($card->company);
            }
            if ($card->job_title) {
                $lines[] = 'TITLE:'.$clean($card->job_title);
            }
            if ($card->phone) {
                $lines[] = 'TEL;TYPE=CELL,VOICE:'.$clean($card->phone);
            }
            if ($card->email) {
                $lines[] = 'EMAIL;TYPE=PREF,INTERNET:'.$clean($card->email);
            }
            if ($card->website) {
                $lines[] = 'URL:'.$clean($card->website);
            }
            if ($card->telegram_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=telegram:'.$clean($card->telegram_url);
            }
            if ($card->github_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=github:'.$clean($card->github_url);
            }
            if ($card->zalo_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=zalo:'.$clean($card->zalo_url);
            }
            if ($card->facebook_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=facebook:'.$clean($card->facebook_url);
            }
            if ($card->linkedin_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=linkedin:'.$clean($card->linkedin_url);
            }
            if ($card->whatsapp_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=whatsapp:'.$clean($card->whatsapp_url);
            }
            if ($card->viber_url) {
                $lines[] = 'X-SOCIALPROFILE;TYPE=viber:'.$clean($card->viber_url);
            }
            if ($card->bio) {
                $lines[] = 'NOTE:'.$clean($card->bio);
            }

            $lines[] = 'END:VCARD';

            return response(implode("\r\n", $lines)."\r\n", 200, [
                'Content-Type' => 'text/vcard; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="Minh-'.$card->code.'.vcf"',
            ]);
        }

        return view('minh_card.show', compact('card'));
    }
}
