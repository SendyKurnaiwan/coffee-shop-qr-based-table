<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use Illuminate\Http\Request;

class CrudController extends Controller
{
    public function index()
    {
        $data = Crud::all();
        return view('/menu/crud', compact('data'));
    }
    public function kopibigen()
    {
        return view('index');
    }
    public function menu()
    {
        $data = Crud::all();
        $tableUserId = auth()->check() ? auth()->id() : null;
        return view('menu', compact('data', 'tableUserId'));
    }
    public function order()
    {
        $data = Crud::all();
        return view('order', compact('data'));
    }
    public function tambah()
    {
        return view('/menu/tambah');
    }
    public function insert(Request $request)
    {
        // dd($request->all());
        $data = Crud::create($request->all());
        if ($request->hasFile('foto')) {
            $request->file('foto')->move('fotomenu/', $request->file('foto')->GetClientOriginalName());
            $data->foto = $request->file('foto')->GetClientOriginalName();
            $data->save();
        }
        return redirect()->route('admin')->with('success', ' Data Berhasil Di Buat');
    }
    public function edit($id)
    {
        $data = Crud::find($id);
        return view('/menu/edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $data = Crud::find($id);
        $data->update($request->all());
        return redirect()->route('admin')->with('success', ' Data Berhasil Di Ubah');
    }
    public function hapus($id)
    {
        $data = Crud::find($id);
        $data->delete();
        return redirect()->route('admin')->with('success', ' Data Berhasil Di Hapus');
    }
}
