@extends('layouts.app')

@section('content')
  <div class="w-full px-3 mx-auto">
    <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white">
      <div class="p-6 pb-0 mb-0 border-b rounded-t-2xl">
        <h6>Edit Kategori</h6>
      </div>
      <div class="flex-auto p-6">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
          @csrf @method('PUT')
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Nama</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required maxlength="100" class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Jenis</label>
            <select name="kind" required class="border border-gray-300 rounded-lg py-2 px-3 w-full">
              <option value="expense" @selected(old('kind', $category->kind)==='expense')>Pengeluaran</option>
              <option value="income" @selected(old('kind', $category->kind)==='income')>Pemasukan</option>
            </select>
          </div>
          <div class="md:col-span-2 mt-2">
            <button class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" type="submit">Simpan</button>
            <a class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" href="{{ route('categories.index') }}">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
