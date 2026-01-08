<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = User::all();
        return view('/meja/meja', compact('data'));
    }
    public function tambah()
    {
        return view('/meja/tambah');
    }
    public function insert(Request $request)
    {
        // dd($request->all());
        $data = User::create($request->all());
        $data->save();
        return redirect()->route('meja')->with('success', ' Data Berhasil Di Buat');
    }
    public function edit($id)
    {
        $data = User::find($id);
        return view('/meja/edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $data = User::find($id);
        $data->update($request->all());
        return redirect()->route('meja')->with('success', ' Data Berhasil Di Ubah');
    }
    public function hapus($id)
    {
        $data = User::find($id);
        $data->delete();
        return redirect()->route('meja')->with('success', ' Data Berhasil Di Hapus');
    }
}
