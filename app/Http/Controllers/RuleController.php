<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        $rules = Rule::orderBy('created_at', 'desc')->get();
        return view('superadmin.rules.index', compact('rules'));
    }

    public function create()
    {
        return view('superadmin.rules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'pola' => 'required|string',
            'deskripsi' => 'nullable|string'
        ]);

        Rule::create([
            'kategori' => $request->kategori,
            'pola' => $request->pola,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('superadmin.rules.index')->with('success', 'Rule berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $rule = Rule::findOrFail($id);
        return view('superadmin.rules.edit', compact('rule'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string',
            'pola' => 'required|string',
            'deskripsi' => 'nullable|string'
        ]);

        $rule = Rule::findOrFail($id);
        $rule->update([
            'kategori' => $request->kategori,
            'pola' => $request->pola,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('superadmin.rules.index')->with('success', 'Rule berhasil diupdate!');
    }

    public function destroy($id)
    {
        $rule = Rule::findOrFail($id);
        $rule->delete();

        return redirect()->route('superadmin.rules.index')->with('success', 'Rule berhasil dihapus!');
    }
}