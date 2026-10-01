<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with([
            'roles',
            'stocks',
        ])
            ->latest()
            ->get();

        $stocks = Stock::query()
            ->orderBy('stock_code')
            ->get();

        return view('users.index', compact(
            'users',
            'stocks'
        ));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],

            'role' => [
                'required',
                Rule::in(['admin', 'user']),
            ],

            'telegram_chat_id' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telegram_chat_id' => $validated['telegram_chat_id'] ?? null,
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user)
    {
        $user->load([
            'roles',
            'stocks',
        ]);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load('roles');

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
            ],

            'role' => [
                'required',
                Rule::in(['admin', 'user']),
            ],

            'telegram_chat_id' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telegram_chat_id' => $validated['telegram_chat_id'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        $user->syncRoles([
            $validated['role'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun sendiri.'
            );
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }


    /*
    |--------------------------------------------------------------------------
    | User Stock Management
    |--------------------------------------------------------------------------
    */

    /**
     * Tambahkan satu stock ke user.
     */
    public function storeStock(Request $request, User $user)
    {
        $validated = $request->validate([
            'stock_code' => [
                'required',
                'string',
                'exists:stocks,stock_code',
            ],
        ]);

        $stockCode = $validated['stock_code'];

        // Cek agar tidak menambahkan saham yang sudah dimiliki user.
        if (
            $user->stocks()
                ->where('stocks.stock_code', $stockCode)
                ->exists()
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    "Saham {$stockCode} sudah terdaftar untuk user {$user->name}."
                );
        }

        $user->stocks()->attach($stockCode);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                "Saham {$stockCode} berhasil ditambahkan ke {$user->name}."
            );
    }


    /**
     * Hapus satu stock dari user.
     */
    public function destroyStock(
        User $user,
        string $stockCode
    ) {
        $stock = Stock::query()
            ->where('stock_code', $stockCode)
            ->first();

        if (!$stock) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    "Saham {$stockCode} tidak ditemukan."
                );
        }

        $detached = $user->stocks()->detach($stockCode);

        if ($detached === 0) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    "Saham {$stockCode} tidak terdaftar pada user {$user->name}."
                );
        }

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                "Saham {$stockCode} berhasil dihapus dari {$user->name}."
            );
    }
}
