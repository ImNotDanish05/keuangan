<!DOCTYPE html>
<html lang="id" class="dark">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Keuangan' }}</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argon/assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('argon/assets/img/logo.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="{{ asset('argon/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('argon/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="{{ asset('argon/assets/css/argon-dashboard-tailwind.css?v=1.0.1') }}" rel="stylesheet" />
    <link href="{{ asset('argon/custom.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
  </head>
  <body class="m-0 font-sans antialiased font-normal text-base leading-default text-white bg-gray-900" style="background-image:url('{{ asset('argon/assets/img/danish05/background.png') }}'); background-size:cover; background-position:center; background-attachment:fixed;">
    <!-- Sidenav -->
    <aside class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white dark:bg-slate-850 border-0 shadow-xl xl:ml-6 max-w-64 ease-nav-brand z-990 rounded-2xl xl:left-0 xl:translate-x-0" aria-expanded="false">
      <div class="h-19">
        <a class="flex items-center px-8 py-6 m-0 text-sm whitespace-nowrap text-white" href="{{ route('dashboard') }}">
          <img src="{{ asset('argon/assets/img/logo.png') }}" class="h-8 w-8 mr-2 shrink-0" alt="logo" />
          <span class="font-semibold">Keuangan</span>
        </a>
      </div>
      <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
      <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
          <li class="mt-0.5 w-full">
            <a class="py-2.7 my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 text-sm font-semibold text-white" href="{{ route('dashboard') }}">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5"><i class="ni ni-tv-2 text-blue-500"></i></div>
              <span class="ml-1">Dashboard</span>
            </a>
          </li>
          <li class="mt-0.5 w-full">
            <a class="py-2.7 my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 text-sm font-semibold text-white" href="{{ route('expenses.index') }}">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5"><i class="ni ni-money-coins text-rose-500"></i></div>
              <span class="ml-1">Pengeluaran</span>
            </a>
          </li>
          <li class="mt-0.5 w-full">
            <a class="py-2.7 my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 text-sm font-semibold text-white" href="{{ route('incomes.index') }}">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5"><i class="ni ni-chart-bar-32 text-emerald-500"></i></div>
              <span class="ml-1">Pemasukan</span>
            </a>
          </li>
          @auth
          @if (in_array(auth()->user()->role, ['owner','admin']))
          <li class="mt-0.5 w-full">
            <a class="py-2.7 my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 text-sm font-semibold text-white" href="{{ route('users.index') }}">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5"><i class="ni ni-single-02 text-white"></i></div>
              <span class="ml-1">Users</span>
            </a>
          </li>
          @endif
          @endauth
          <li class="mt-0.5 w-full">
            <a class="py-2.7 my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 text-sm font-semibold text-white" href="{{ route('categories.index') }}">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5"><i class="ni ni-tag text-white"></i></div>
              <span class="ml-1">Kategori</span>
            </a>
          </li>
          <li class="mt-0.5 w-full">
            <a class="py-2.7 my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 text-sm font-semibold text-white" href="{{ route('reports.export', ['year'=>now()->year,'month'=>now()->month]) }}">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5"><i class="ni ni-cloud-download-95 text-blue-500"></i></div>
              <span class="ml-1">Export CSV</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="mx-4 my-4">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="inline-block w-full px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg">Logout</button>
        </form>
      </div>
    </aside>

    <!-- Main content wrapper -->
    <div class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68">
      <main class="w-full px-6 py-6 mx-auto">
      @if (session('success'))
        <div class="px-4 py-2 mb-4 text-sm text-white bg-emerald-500 rounded-md">{{ session('success') }}</div>
      @endif
      @if (session('status'))
        <div class="px-4 py-2 mb-4 text-sm text-white bg-sky-500 rounded-md">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="px-4 py-2 mb-4 text-sm text-white bg-rose-500 rounded-md">
          <ul class="ml-4 list-disc">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
      </main>

      <footer class="w-full px-6 pb-6 mx-auto mt-6 text-center">
        <small class="text-slate-500">&copy; {{ date('Y') }} ImNotDanish05</small>
      </footer>
    </div>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
      // Make Chart.js text readable on dark backgrounds
      if (window.Chart) {
        const white = '#fff';
        const grid = 'rgba(255,255,255,0.12)';
        Chart.defaults.color = white; // default font color (ticks, legends, tooltips)
        Chart.defaults.borderColor = grid; // default border/grid tone
        // Be explicit for common plugins
        Chart.defaults.plugins = Chart.defaults.plugins || {};
        Chart.defaults.plugins.legend = Chart.defaults.plugins.legend || {};
        Chart.defaults.plugins.legend.labels = Chart.defaults.plugins.legend.labels || {};
        Chart.defaults.plugins.legend.labels.color = white;
        Chart.defaults.plugins.title = Chart.defaults.plugins.title || {};
        Chart.defaults.plugins.title.color = white;
        Chart.defaults.plugins.tooltip = Chart.defaults.plugins.tooltip || {};
        Chart.defaults.plugins.tooltip.titleColor = white;
        Chart.defaults.plugins.tooltip.bodyColor = white;
        // Nudge common scales (category/linear) so grid/ticks are light
        Chart.defaults.scales = Chart.defaults.scales || {};
        ['category','linear'].forEach(k => {
          Chart.defaults.scales[k] = Chart.defaults.scales[k] || {};
          Chart.defaults.scales[k].grid = Object.assign({ color: grid }, Chart.defaults.scales[k].grid);
          Chart.defaults.scales[k].ticks = Object.assign({ color: white }, Chart.defaults.scales[k].ticks);
        });
      }
    </script>
    <script src="{{ asset('argon/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <script src="{{ asset('argon/assets/js/argon-dashboard-tailwind.js?v=1.0.1') }}" async></script>
    <script src="{{ asset('assets/app.js') }}" defer></script>
  </body>
</html>
