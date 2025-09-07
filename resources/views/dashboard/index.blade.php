@extends('layouts.app')

@section('content')
  <div class="w-full px-3 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-slate-700 dark:text-slate-200">
          <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl">
            <h6 class="text-white">Ringkasan Bulanan</h6>
          </div>
          <div class="flex-auto p-6">
            <form class="flex flex-wrap items-end gap-3" method="get" action="{{ route('dashboard') }}">
              <div>
                <label class="block mb-1 text-xs font-bold text-white">Bulan</label>
                <select name="month" class="text-white border border-gray-300 rounded-lg py-2 px-3">
                  @for ($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" @selected($m==$month)>{{ $m }}</option>
                  @endfor
                </select>
              </div>
              <div>
                <label class="block mb-1 text-xs font-bold text-white">Tahun</label>
                <input type="number" name="year" value="{{ $year }}" min="2000" max="2100" class="text-white border border-gray-300 rounded-lg py-2 px-3" />
              </div>
              <div class="ml-auto flex gap-2">
                <button class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" type="submit">Terapkan</button>
                <a class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" href="{{ route('reports.export', ['year'=>$year,'month'=>$month]) }}">Export CSV</a>
                <a class="px-4 py-2 text-sm font-bold text-white bg-rose-600 rounded-lg" href="{{ route('expenses.create') }}">+ Pengeluaran</a>
                <a class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 rounded-lg" href="{{ route('incomes.create') }}">+ Pemasukan</a>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="w-full max-w-full px-3 mb-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div class="p-6 bg-emerald-700 rounded-2xl shadow-xl text-slate-700 dark:text-slate-200">
            <p class="text-xs font-semibold text-white">Total Pemasukan</p>
            <h3 class="mt-2 text-2xl text-white font-bold ">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
          </div>
          <div class="p-6 bg-rose-700 rounded-2xl shadow-xl text-slate-700 dark:text-slate-200">
            <p class="text-xs font-semibold text-white">Total Pengeluaran</p>
            <h3 class="mt-2 text-2xl font-bold text-white">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
          </div>
          <div class="p-6 bg-white dark:bg-slate-850 rounded-2xl shadow-xl text-slate-700 dark:text-slate-200">
            <p class="text-xs font-semibold text-white">Saldo {{ $balance>=0 ? '' : '[Ngutang]' }}</p>
            <h3 class="mt-2 text-2xl font-bold text-white {{ $balance>=0 ? 'text-emerald-600' : 'text-rose-600' }}">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
          </div>
        </div>
      </div>

      <div class="w-full max-w-full px-3 mb-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white dark:text-slate-200">
            <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl"><h6 class="text-slate-700 dark:text-white">Ringkasan Bulanan</h6></div>
            <div class="flex-auto p-6"><canvas id="chartSummary"></canvas></div>
          </div>
          <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white dark:text-slate-200">
            <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl"><h6 class="text-white">Pengeluaran per Kategori</h6></div>
            <div class="flex-auto p-6"><canvas id="chartDonut"></canvas></div>
          </div>
        </div>
      </div>

      <div class="w-full max-w-full px-3 mb-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white dark:text-slate-200">
            <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl"><h6>Saldo per Bulan</h6></div>
            <div class="flex-auto p-6"><canvas id="chartSaldo"></canvas></div>
          </div>
          <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white dark:text-slate-200">
            <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl"><h6>Pemasukan vs Pengeluaran (Stacked)</h6></div>
            <div class="flex-auto p-6"><canvas id="chartStacked"></canvas></div>
          </div>
        </div>
      </div>

      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-slate-700 dark:text-slate-200">
          <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl text-white"><h6>Detail Harian (Bulan Aktif)</h6></div>
          <div class="flex-auto p-6"><canvas id="chartDaily"></canvas></div>
        </div>
      </div>

      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white dark:text-slate-200">
          <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl">
            <h6>Transaksi Terbaru</h6>
          </div>
          <div class="flex-auto p-6 overflow-x-auto">
            <table class="items-center w-full mb-0 align-top border-collapse text-white">
              <thead class="align-bottom">
                <tr>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Tanggal</th>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Jenis</th>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Kategori</th>
                  <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Nominal</th>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Deskripsi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($latest as $t)
                  <tr>
                    <td class="p-2 align-middle bg-transparent whitespace-nowrap">
                      <span class="ml-2 text-sm font-semibold text-white">{{ $t['date']->format('Y-m-d') }}</span>
                    </td>
                    <td class="p-2 align-middle bg-transparent whitespace-nowrap">
                      <span class="ml-2 text-sm font-semibold text-white">{{ $t['type']=='income' ? 'Pemasukan' : 'Pengeluaran' }}</span>
                    </td>
                    <td class="p-2 align-middle bg-transparent whitespace-nowrap">
                      <span class="ml-2 text-sm font-semibold text-white">{{ $t['category'] }}</span>
                    </td>
                    <td class="p-2 text-right align-middle bg-transparent whitespace-nowrap">
                      <span class="ml-2 text-sm font-bold {{ $t['type']=='income' ? 'text-emerald-600' : 'text-rose-600' }}">Rp {{ number_format($t['amount'], 0, ',', '.') }}</span>
                    </td>
                    <td class="p-2 align-middle bg-transparent">
                      <span class="ml-2 text-sm text-white">{{ $t['description'] }}</span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="p-4 text-center text-sm text-white">Belum ada transaksi.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white dark:text-slate-200">
          <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl">
            <h6>Pengeluaran per Kategori</h6>
          </div>
          <div class="flex-auto p-6 overflow-x-auto">
            <table class="items-center w-full mb-0 align-top border-collapse text-white">
              <thead class="align-bottom">
                <tr>
                  <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Kategori</th>
                  <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($expenseByCategory as $row)
                  <tr>
                    <td class="p-2 align-middle bg-transparent whitespace-nowrap">
                      <span class="ml-2 text-sm font-semibold text-white">{{ $row->label }}</span>
                    </td>
                    <td class="p-2 text-right align-middle bg-transparent whitespace-nowrap">
                      <span class="ml-2 text-sm font-bold text-white">Rp {{ number_format($row->total, 0, ',', '.') }}</span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="2" class="p-4 text-center text-sm text-white">Tidak ada data.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const chart = @json($chart);
    const gray = '#475569', grayLight = '#94a3b8', rose = '#ef4444', emerald = '#22c55e';

    // 1) Summary bar
    new Chart(document.getElementById('chartSummary'), {
      type: 'bar',
      data: {
        labels: chart.chartSummary.labels,
        datasets: [{
          label: 'Jumlah',
          data: chart.chartSummary.data,
          backgroundColor: [emerald, rose],
          borderRadius: 6,
        }]
      },
      options: { plugins: { legend: { display: false } }, scales: { x: { grid: { display:false }}, y: { beginAtZero:true } } }
    });

    // 2) Donut
    new Chart(document.getElementById('chartDonut'), {
      type: 'doughnut',
      data: {
        labels: chart.chartDonut.labels,
        datasets: [{
          data: chart.chartDonut.data,
          backgroundColor: ['#64748b','#334155','#0ea5e9','#f59e0b','#22c55e','#ef4444','#a78bfa','#14b8a6','#eab308','#f97316'],
        }]
      },
      options: { plugins: { legend: { position:'bottom' } }, cutout: '60%'}
    });

    // 3) Saldo trend
    new Chart(document.getElementById('chartSaldo'), {
      type: 'line',
      data: {
        labels: chart.monthLabels,
        datasets: [{
          label: 'Saldo', data: chart.saldoMonths, fill:false, borderColor: emerald, backgroundColor: emerald, tension: .3
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // 4) Stacked bar income vs expense
    new Chart(document.getElementById('chartStacked'), {
      type: 'bar',
      data: {
        labels: chart.monthLabels,
        datasets: [
          { label: 'Pemasukan', data: chart.incomeMonths, backgroundColor: emerald, stack: 'tot' },
          { label: 'Pengeluaran', data: chart.expenseMonths, backgroundColor: rose, stack: 'tot' },
        ]
      },
      options: { scales: { x: { stacked:true }, y: { stacked:true, beginAtZero:true } } }
    });

    // 5) Daily detail
    new Chart(document.getElementById('chartDaily'), {
      type: 'bar',
      data: {
        labels: chart.dayLabels,
        datasets: [
          { label: 'Pemasukan', data: chart.incomeDaily, backgroundColor: emerald },
          { label: 'Pengeluaran', data: chart.expenseDaily, backgroundColor: rose },
        ]
      },
      options: { scales: { y: { beginAtZero:true } }, plugins: { legend: { position:'bottom' } } }
    });
  });
</script>
@endpush
