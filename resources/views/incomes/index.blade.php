@extends('layouts.app')

@section('content')
  <div class="w-full px-3 mx-auto">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-xl font-bold text-white">Pemasukan</h1>
      <a class="px-4 py-2 text-sm font-bold text-white bg-emerald-500 rounded-lg" href="{{ route('incomes.create') }}">+ Tambah</a>
    </div>

    <div class="relative flex flex-col min-w-0 mb-6 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white">
      <div class="p-6">
        <form class="flex flex-wrap items-end gap-3" method="get" action="{{ route('incomes.index') }}">
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Dari</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="border border-gray-300 rounded-lg py-2 px-3" />
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Sampai</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="border border-gray-300 rounded-lg py-2 px-3" />
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Kategori</label>
            <select name="category_id" class="border border-gray-300 rounded-lg py-2 px-3">
              <option value="">Semua</option>
              @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="min-w-[220px]">
            <label class="block mb-1 text-xs font-bold text-white">Cari</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="deskripsi..." class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
          </div>
          <div class="ml-auto">
            <button class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" type="submit">Filter</button>
          </div>
        </form>
        <div class="mt-3 text-sm text-white">Total: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></div>
      </div>
    </div>

    <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white">
      <div class="flex-auto p-6 overflow-x-auto">
        <table class="items-center w-full mb-0 align-top border-collapse text-white">
          <thead class="align-bottom">
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-white opacity-70">Tanggal</th>
              <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-white opacity-70">Kategori</th>
              <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-white opacity-70">Nominal</th>
              <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-white opacity-70">Deskripsi</th>
              <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-white opacity-70"></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($incomes as $i)
              <tr>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm font-semibold text-white">{{ $i->received_at->format('Y-m-d') }}</span></td>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm font-semibold text-white">{{ $i->category?->name }}</span></td>
                <td class="p-2 text-right align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm font-bold text-emerald-600">Rp {{ number_format($i->amount, 0, ',', '.') }}</span></td>
                <td class="p-2 align-middle bg-transparent"><span class="ml-2 text-sm">{{ $i->description }}</span></td>
                <td class="p-2 text-right align-middle bg-transparent whitespace-nowrap">
                  <a class="px-3 py-1 mr-1 text-xs font-bold text-white bg-slate-700 rounded-lg" href="{{ route('incomes.edit', $i) }}">Edit</a>
                  <form action="{{ route('incomes.destroy', $i) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item ini?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 text-xs font-bold text-white bg-rose-600 rounded-lg" type="submit">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="p-4 text-center text-sm text-white">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">{{ $incomes->links() }}</div>
  </div>
@endsection
