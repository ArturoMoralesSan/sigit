<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;

class MaterialController extends Controller
{
    public function consulta()
    {
        return view('principal.material');
    }

    public function buscar(Request $request)
    {
        $search = $request->search;

        if (!$search) {
            return response()->json([], 422);
        }

        $materials = Equipment::query()

            ->when($search, function ($query) use ($search) {

                $query->where('product', 'LIKE', "%{$search}%")
                      ->orWhere('brand', 'LIKE', "%{$search}%")
                      ->orWhere('category', 'LIKE', "%{$search}%");

            })
            ->select(
                'id',
                'product',
                'category',
                'brand',
                'quantity',
                'area',
                'image',
                'model'
            )
            ->orderBy('product')
            ->limit(20)
            ->get();

        return response()->json($materials);
    }
}
