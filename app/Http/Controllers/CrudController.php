<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use Illuminate\Http\Request;

class CrudController extends Controller
{
    public function index()
    {
        $data = Crud::all();
        return view('crud',compact('data'));
    }
    public function kopibigen()
    {
        return view('index');
    }
    public function menu()
    {
        $data = Crud::all();
        return view('menu',compact('data'));
    }
    public function tambah()
    {
        return view('tambah');
    }
    public function insert(Request $request)
    {
        // dd($request->all());
        $data = Crud::create($request->all());
        if($request->hasFile('foto'))
        {
            $request->file('foto')->move('fotomenu/', $request->file('foto')->GetClientOriginalName());
            $data->foto = $request->file('foto')->GetClientOriginalName();
            $data->save();

        } 
        return redirect()->route('/admin')->with('success',' Data Berhasil Di Buat');
    }
    public function edit($id)
    {
        $data = Crud::find($id);
        return view('edit', compact('data'));

    }
    public function update(Request $request, $id)
    {
        $data = Crud::find($id);
        $data->update($request->all());
        return redirect()->route('/admin')->with('success',' Data Berhasil Di Ubah');
    }
    public function hapus($id)
    {
        $data = Crud::find($id);
        $data->delete();
        return redirect()->route('/admin')->with('success',' Data Berhasil Di Hapus');
    }
}
