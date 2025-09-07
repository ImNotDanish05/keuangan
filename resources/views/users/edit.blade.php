@extends('layouts.app')

@section('content')
  <div class="w-full px-3 mx-auto">
    <div class="relative flex flex-col min-w-0 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white">
      <div class="p-6 pb-0 mb-0 border-b border-white/20 rounded-t-2xl">
        <h6>Edit User</h6>
      </div>
      <div class="flex-auto p-6">
        <form method="POST" action="{{ route('users.update', $user) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
          @csrf @method('PUT')
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
            @error('name')<div class="mt-1 text-xs text-rose-400">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
            @error('username')<div class="mt-1 text-xs text-rose-400">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Email (opsional)</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
            @error('email')<div class="mt-1 text-xs text-rose-400">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Role</label>
            @php $actor = auth()->user(); @endphp
            <select name="role" class="border border-gray-300 rounded-lg py-2 px-3 w-full">
              @if ($actor && $actor->role === 'owner')
                <option value="owner" @selected(old('role', $user->role)==='owner')>Owner</option>
              @endif
              <option value="admin" @selected(old('role', $user->role)==='admin')>Admin</option>
              <option value="user" @selected(old('role', $user->role)==='user')>User</option>
            </select>
            @error('role')<div class="mt-1 text-xs text-rose-400">{{ $message }}</div>@enderror
          </div>

          <div>
            <label class="block mb-1 text-xs font-bold text-white">Password (kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
            @error('password')<div class="mt-1 text-xs text-rose-400">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="border border-gray-300 rounded-lg py-2 px-3 w-full" />
          </div>

          <div class="md:col-span-2 flex items-center gap-2 mt-2">
            <label class="inline-flex items-center">
              <input type="checkbox" name="is_approved" value="1" class="mr-2" @checked(old('is_approved', $user->is_approved))> <span>Approved</span>
            </label>
          </div>

          <div class="md:col-span-2 mt-2">
            <button class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" type="submit">Simpan</button>
            <a class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" href="{{ route('users.index') }}">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

