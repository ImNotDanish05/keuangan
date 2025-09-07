@extends('layouts.app')

@section('content')
  <div class="w-full px-3 mx-auto">
    <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white">
      <div class="p-6 pb-0 mb-0 border-b rounded-t-2xl">
        <h6>Tambah Pengeluaran</h6>
      </div>
      <div class="flex-auto p-6">
        <form method="POST" action="{{ route('expenses.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
          @csrf
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Kategori</label>
            <select name="category_id" required class="border border-gray-300 rounded-lg py-2 px-3 w-full">
              <option value="">-- Pilih --</option>
              @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Tanggal</label>
            <input type="date" name="spent_at" value="{{ old('spent_at', date('Y-m-d')) }}" required class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Nominal (Rp)</label>
            <input type="number" name="amount" value="{{ old('amount') }}" min="0" step="0.01" required class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
          </div>
          <div class="md:col-span-2">
            <label class="block mb-1 text-xs font-bold text-white">Deskripsi</label>
            <textarea name="description" rows="3" class="border border-gray-300 rounded-lg py-2 px-3 w-full">{{ old('description') }}</textarea>
          </div>
          <div class="md:col-span-2 mt-2">
            <button class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" type="submit">Simpan</button>
            <a class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" href="{{ route('expenses.index') }}">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
