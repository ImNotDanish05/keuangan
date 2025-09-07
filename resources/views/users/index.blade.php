@extends('layouts.app')

@section('content')
  <div class="w-full px-3 mx-auto">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-xl font-bold text-white">Users</h1>
      <a class="px-4 py-2 text-sm font-bold text-white bg-slate-700 rounded-lg" href="{{ route('users.create') }}">+ Create</a>
    </div>

    <div class="relative flex flex-col min-w-0 mb-6 bg-white dark:bg-slate-850 shadow-xl rounded-2xl text-white">
      <div class="p-6">
        <form class="flex flex-wrap items-end gap-3" method="get" action="{{ route('users.index') }}">
          <div class="min-w-[220px]">
            <label class="block mb-1 text-xs font-bold text-white">Search</label>
            <input type="text" name="q" value="{{ $q }}" class="border border-gray-300 rounded-lg py-2 px-3 w-full" placeholder="name/username/email" />
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Role</label>
            <select name="role" class="border border-gray-300 rounded-lg py-2 px-3">
              <option value="">All</option>
              <option value="owner" @selected($role==='owner')>Owner</option>
              <option value="admin" @selected($role==='admin')>Admin</option>
              <option value="user" @selected($role==='user')>User</option>
            </select>
          </div>
          <div>
            <label class="block mb-1 text-xs font-bold text-white">Approval</label>
            <select name="status" class="border border-gray-300 rounded-lg py-2 px-3">
              <option value="">All</option>
              <option value="approved" @selected($status==='approved')>Approved</option>
              <option value="pending" @selected($status==='pending')>Pending</option>
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
          <thead>
            <tr>
              <th class="px-6 py-3 font-bold text-left uppercase bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Name</th>
              <th class="px-6 py-3 font-bold text-left uppercase bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Username</th>
              <th class="px-6 py-3 font-bold text-left uppercase bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Email</th>
              <th class="px-6 py-3 font-bold text-left uppercase bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Role</th>
              <th class="px-6 py-3 font-bold text-left uppercase bg-transparent border-b border-white/20 text-xxs text-white opacity-70">Status</th>
              <th class="px-6 py-3 font-bold text-right uppercase bg-transparent border-b border-white/20 text-xxs text-white opacity-70"></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $u)
              <tr>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm font-semibold text-white">{{ $u->name }}</span></td>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm font-semibold text-white">{{ $u->username }}</span></td>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm">{{ $u->email }}</span></td>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap"><span class="ml-2 text-sm font-semibold">{{ ucfirst($u->role) }}</span></td>
                <td class="p-2 align-middle bg-transparent whitespace-nowrap">
                  @if ($u->is_approved)
                    <span class="ml-2 text-xs font-bold text-emerald-400">Approved</span>
                  @else
                    <span class="ml-2 text-xs font-bold text-rose-400">Pending</span>
                  @endif
                </td>
                <td class="p-2 text-right align-middle bg-transparent whitespace-nowrap">
                  <a class="px-3 py-1 mr-1 text-xs font-bold text-white bg-slate-700 rounded-lg" href="{{ route('users.edit', $u) }}">Edit</a>
                  <form action="{{ route('users.toggleApproval', $u) }}" method="POST" class="inline">
                    @csrf @method('PATCH')
                    <button class="px-3 py-1 mr-1 text-xs font-bold text-white bg-sky-600 rounded-lg" type="submit">{{ $u->is_approved ? 'Unapprove' : 'Approve' }}</button>
                  </form>
                  <form action="{{ route('users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 text-xs font-bold text-white bg-rose-600 rounded-lg" type="submit">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="p-4 text-center text-sm text-white">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="p-4">{{ $users->links() }}</div>
    </div>
  </div>
@endsection

