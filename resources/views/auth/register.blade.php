<!DOCTYPE html>
<html lang="id" class="dark">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argon/assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('argon/assets/img/logo.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('argon/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('argon/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="{{ asset('argon/assets/css/argon-dashboard-tailwind.css?v=1.0.1') }}" rel="stylesheet" />
  </head>
  <body class="m-0 font-sans antialiased font-normal bg-white text-base leading-default text-slate-500" style="background-image:url('{{ asset('argon/assets/img/danish05/background.png') }}'); background-size:cover; background-position:center; background-attachment:fixed;">
    <main class="mt-0 transition-all duration-200 ease-soft-in-out">
      <section>
        <div class="relative flex items-center min-h-screen p-0 overflow-hidden bg-center bg-cover">
          <div class="container z-1">
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 mx-auto md:flex-0 shrink-0 md:w-6/12 lg:w-4/12">
                <div class="relative flex flex-col p-6 bg-white dark:bg-slate-850 text-slate-700 dark:text-slate-200 border-0 shadow-xl rounded-2xl bg-clip-border">
                  <div class="mb-4 text-center">
                    <h3 class="font-bold">Daftar</h3>
                    <p class="mb-0 text-sm">Buat akun baru</p>
                  </div>

                  @if ($errors->any())
                    <div class="px-3 py-2 mb-4 text-sm text-white bg-rose-500 rounded-md">
                      <ul class="ml-4 list-disc">
                        @foreach($errors->all() as $e)
                          <li>{{ $e }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  <form role="form" method="POST" action="{{ url('/register') }}" class="text-left">
                    @csrf
                    <label class="ml-1 text-sm font-bold text-slate-700">Nama</label>
                    <div class="mb-3">
                      <input name="name" type="text" value="{{ old('name') }}" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white/80 bg-clip-padding py-2 px-3 font-normal text-slate-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                    <label class="ml-1 text-sm font-bold text-slate-700">Username</label>
                    <div class="mb-3">
                      <input name="username" type="text" value="{{ old('username') }}" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white/80 bg-clip-padding py-2 px-3 font-normal text-slate-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                    <label class="ml-1 text-sm font-bold text-slate-700">Email (opsional)</label>
                    <div class="mb-3">
                      <input name="email" type="email" value="{{ old('email') }}" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white/80 bg-clip-padding py-2 px-3 font-normal text-slate-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                    <label class="ml-1 text-sm font-bold text-slate-700">Password</label>
                    <div class="mb-3">
                      <input name="password" type="password" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white/80 bg-clip-padding py-2 px-3 font-normal text-slate-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                    <div class="text-center">
                      <button class="inline-block w-full px-5 py-2.5 mt-6 mb-2 text-sm font-bold text-center text-white uppercase align-middle transition-all bg-slate-700 border-0 rounded-lg cursor-pointer shadow-md bg-150 bg-x-25 hover:shadow-soft-xs hover:-translate-y-px active:opacity-85" type="submit">
                        Daftar
                      </button>
                    </div>
                  </form>
                  <p class="mt-4 mb-0 text-sm text-center">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-slate-700">Login</a>
                  </p>
                </div>
              </div>
              <div class="absolute top-0 right-0 hidden w-1/2 h-full pr-0 my-auto text-center -mr-12 md:flex">
                <div class="relative flex flex-col justify-center w-full h-full px-24 m-4 overflow-hidden bg-cover rounded-xl bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg')]">
                  <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover opacity-60 bg-gradient-to-tl from-blue-500 to-violet-500"></span>
                  <h4 class="z-20 mt-12 font-bold text-white">"Attention is the new currency"</h4>
                  <p class="z-20 text-white">The more effortless the writing looks, the more effort the writer actually put into the process.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
    <script src="{{ asset('argon/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <script src="{{ asset('argon/assets/js/argon-dashboard-tailwind.js?v=1.0.1') }}" async></script>
  </body>
</html>
