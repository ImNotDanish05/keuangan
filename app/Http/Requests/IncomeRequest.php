<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IncomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('kind', 'income'),
            ],
            'amount' => ['required','numeric','min:0.01'],
            'received_at' => ['required','date'],
            'description' => ['nullable','string','max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid untuk pemasukan.',
            'amount.required' => 'Nominal wajib diisi.',
            'amount.numeric' => 'Nominal harus angka.',
            'amount.min' => 'Nominal minimal Rp 0,01.',
            'received_at.required' => 'Tanggal wajib diisi.',
            'received_at.date' => 'Format tanggal tidak valid.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}

