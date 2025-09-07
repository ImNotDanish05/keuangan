<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category')?->id;
        $unique = Rule::unique('categories', 'name')->where('kind', $this->input('kind'));
        if ($id) {
            $unique = $unique->ignore($id);
        }

        return [
            'name' => ['required','string','max:100', $unique],
            'kind' => ['required', Rule::in(['expense','income'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah dipakai untuk jenis tersebut.',
            'kind.required' => 'Jenis kategori wajib diisi.',
            'kind.in' => 'Jenis kategori harus expense atau income.',
        ];
    }
}

