<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function index()
    {
        $keys = ApiKey::latest()->get();

        return view('admin.api_keys.index', compact('keys'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        [$plaintext] = ApiKey::generate($validated['name']);

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'API key "' . $validated['name'] . '" berhasil dibuat. Simpan baik-baik, key ini hanya ditampilkan sekali!')
            ->with('plaintext_key', $plaintext);
    }

    public function destroy(ApiKey $apiKey)
    {
        $apiKey->revoke();

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'API key "' . $apiKey->name . '" berhasil di-revoke. Key tersebut sudah tidak bisa dipakai lagi.');
    }
}
