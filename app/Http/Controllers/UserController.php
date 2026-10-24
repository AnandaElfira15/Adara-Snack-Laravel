<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $r)
    {
        $rows = User::when($r->q, function ($b, $q) {
            $b->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%");
            });
        })
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

        return view('users.index', ['rows' => $rows]);
    }

    public function create()
    {
        return view('users.form', ['row' => new User]);
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'staff', 'user'])],
        ]);

        $d['password'] = Hash::make($d['password']);

        User::create($d);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.form', ['row' => $user]);
    }

    public function update(Request $r, User $user)
    {
        $d = $r->validate([
            'name' => 'required|string|max:150',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(['admin', 'staff', 'user'])],
        ]);

        if (!empty($d['password'])) {
            $d['password'] = Hash::make($d['password']);
        } else {
            unset($d['password']);
        }

        $user->update($d);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Akun yang sedang digunakan tidak dapat dihapus.'
            );
        }

        if ($user->sales()->exists() || $user->purchases()->exists()) {
            return back()->with(
                'error',
                'Pengguna memiliki riwayat transaksi.'
            );
        }

        $user->delete();

        return back()->with('success', 'Pengguna dihapus.');
    }
}
