@extends('layouts.app')

@section('content')
  <div class="w-full px-3 mx-auto">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-xl font-bold text-white">Kategori</h1>
      <a class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" href="{{ route('categories.create') }}">+ Tambah</a>
    </div>

    <div class="relative flex flex-col min-w-0 mb-6 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white">
      <div class="p-6">
        <form class="flex flex-wrap items-end gap-3" method="get" action="{{ route('categories.index') }}">
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Jenis</label>
            <select name="kind" class="border border-gray-300 rounded-lg py-2 px-3">
              <option value="">Semua</option>
              <option value="expense" @selected(request('kind')==='expense')>Pengeluaran</option>
              <option value="income" @selected(request('kind')==='income')>Pemasukan</option>
            </select>
          </div>
          <div class="ml-auto">
            <button class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" type="submit">Filter</button>
          </div>
        </form>
      </div>
    </div>

    <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white">
      <div class="flex-auto p-6 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse text-white">
          <thead class="align-bottom">
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-white opacity-70">Nama</th>
              <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-white opacity-70">Jenis</th>
              <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-white opacity-70"></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($categories as $c)
              <tr>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm font-semibold text-white">{{ $c->name }}</span></td>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm">{{ $c->kind === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span></td>
                <td class="p-2 text-right align-middle bg-transparent whitespace-nowrap">
                  <a class="px-3 py-1 mr-1 text-xs font-bold text-white bg-slate-700 rounded-lg" href="{{ route('categories.edit', $c) }}">Edit</a>
                  <form action="{{ route('categories.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 text-xs font-bold text-white bg-rose-600 rounded-lg" type="submit">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="3" class="p-4 text-center text-sm text-white">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
  </div>
@endsection
