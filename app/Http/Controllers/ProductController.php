<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(); //MEMBUAT QUERY UNTUK MENGAMBIL DATA DARI TABLE PRODUCTS
        return Inertia::render('product', ['data' => $products]); //RENDER DATA TERSEBUT MENGGUNAKAN INERTIA. 
        //PARAMETER PERTAMA YAKNI product ADALAH NAMA FILE .VUE YANG NANTINYA AKAN KITA BUAT
        //PARAMETER BERIKUTNYA ADALAH DATA YANG AKAN DIPASSING BERISI SELURUH DATA PRODUCTS
    }
    public function store(Request $request)
    {
        //MEMBUAT VALIDASI DATA
        Validator::make($request->all(), [
            'code' => ['required', 'string', 'unique:products,code'],
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'weight' => ['required', 'integer'],
        ])->validate();

        Product::create($request->all()); //MEMBUAT QUERY UNTUK MENYIMPAN DATA
        return redirect()->back()->with(['message' => 'Produk: ' . $request->name . ' Ditambahkan']); //MENGIRIMKAN FLASH MESSAGE 
    }
    public function update(Request $request, $id)
        {
        //VALIDASI DATA
            Validator::make($request->all(), [
                'code' => ['required', 'string', 'unique:products,code,' . $id],
                'name' => ['required', 'string'],
                'price' => ['required', 'integer'],
                'weight' => ['required', 'integer'],
            ])->validate();

            $product = Product::find($id); //QUERY UNTUK MENGAMBIL DATA BERDASARKAN ID
            $product->update($request->all()); //PERBAHARUI DATA
            return redirect()->back()->with(['message' => 'Produk: ' . $request->name . ' Diperbaharui']); //KIRIM FLASH MESSAGE
        }
        public function destroy($id)
        {
            $product = Product::find($id);
            $product->delete();
            return redirect()->back()->with(['message' => 'Produk: ' . $product->name . ' Di hapus']);
        }

}
