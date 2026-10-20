<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MinhCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->isMethod('post') && $this->has('code')) {
            $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
        }
        $this->merge(['active' => $this->boolean('active')]);
    }

    public function rules(): array
    {
        $creating = $this->isMethod('post');
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'zalo' => ['nullable', 'string', 'max:100'],
            'telegram' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'github' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:100'],
            'viber' => ['nullable', 'string', 'max:100'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1500'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'active' => ['required', 'boolean'],
        ];

        if ($creating) {
            $rules['code'] = [
                'required',
                'regex:/^[A-Za-z0-9_-]{2,32}$/',
                'unique:minh_cards,code',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $v = strtoupper((string) $value);
                    if (in_array($v, ['ADMIN', 'LOGIN', 'LOGOUT', 'STORAGE', 'API', 'UP', 'BUILD'], true)) {
                        $fail('Mã thẻ trùng từ khóa hệ thống.');
                    }
                },
            ];
        }

        return $rules;
    }
}
