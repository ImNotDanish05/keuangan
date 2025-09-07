<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $q = trim((string) $request->input('q'));
        $role = $request->input('role');
        $status = $request->input('status'); // approved|pending|null

        $users = User::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%$q%")
                      ->orWhere('username', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%");
                });
            })
            ->when(in_array($role, ['owner','admin','user']), function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when(in_array($status, ['approved','pending']), function ($query) use ($status) {
                $approved = $status === 'approved';
                $query->where('is_approved', $approved);
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', [
            'title' => 'Users',
            'users' => $users,
            'q' => $q,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('users.create', [
            'title' => 'Create User',
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validated();
        $actor = Auth::user();

        if ($actor->role === 'admin' && ($data['role'] ?? 'user') === 'owner') {
            abort(403, 'Admin tidak boleh membuat user owner.');
        }

        // normalize defaults
        $data['is_approved'] = (bool) ($data['is_approved'] ?? false);

        $user = new User();
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'] ?? null;
        $user->password = $data['password']; // hashed by cast
        $user->role = $data['role'];
        $user->is_approved = $data['is_approved'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', [
            'title' => 'Edit User',
            'user' => $user,
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $actor = Auth::user();

        $data = $request->validated();

        if ($actor->role === 'admin' && $user->role === 'owner') {
            abort(403, 'Admin tidak boleh mengubah user owner.');
        }

        if (($data['role'] ?? $user->role) === 'owner' && $actor->role !== 'owner') {
            abort(403, 'Hanya owner yang boleh mempromosikan menjadi owner.');
        }

        // Prevent self-delete-like lockout via role downgrade handled in request after()

        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'] ?? null;
        $user->role = $data['role'];
        if (array_key_exists('is_approved', $data)) {
            $user->is_approved = (bool) $data['is_approved'];
        }
        if (!empty($data['password'])) {
            $user->password = $data['password']; // hashed by cast
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function toggleApproval(User $user)
    {
        $this->authorize('update', $user);
        $user->is_approved = !$user->is_approved;
        $user->save();
        return redirect()->back()->with('status', 'Status approval diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $actorId = Auth::id();
        if ($user->id === $actorId) {
            return back()->with('status', 'Tidak boleh menghapus diri sendiri.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User dihapus.');
    }
}

