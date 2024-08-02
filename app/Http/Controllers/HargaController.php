<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Harga;
use App\Models\Product;
use Illuminate\Http\Request;

class HargaController extends Controller
{
    public function index($id = null) {
        if ($id != null) {
            $hargas = Harga::where('product_id', '=', $id)->get();
        } else {
            $hargas = Harga::with(['product'])->get();
        }

        return view('admin.harga.harga', [
            'hargas' => $hargas,
            'products' => Product::all()
        ]);
    }

    public function edit($id) {
        $hargas = Harga::with(['product'])->find($id);
        return view('admin.harga.editharga',[
            'harga'  => $hargas,
            'product' => Product::all(),
            
        ]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'product' => 'required',
            'harga' => 'required',
            'hari' => 'required',
        ]);

        $hargas = new Harga();
        $hargas->product_id = $request['product'];
        $hargas->harga = $request['harga'];
        $hargas->hari = $request['hari'];
        $hargas->save();

        return redirect(route('harga.index'))->with('message', 'Produk berhasil ditambah!');
    }

    public function update(Request $request, $id) {
        $this->validate($request,[
            'product' => 'required',
            'harga' => 'required',
            'hari' => 'required',
        ]);
        $hargas = Harga::find($id);
        $hargas->product_id = $request['product'];
        $hargas->harga = $request['harga'];
        $hargas->hari = $request['hari'];
        $hargas->save();
        return redirect(route('harga.index'))->with('message', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id) {
        $hargas = Harga::find($id);
        $hargas->delete();
        return redirect(route('harga.index'))->with('message', 'Kategori berhasil dihapus!');
    }
}
