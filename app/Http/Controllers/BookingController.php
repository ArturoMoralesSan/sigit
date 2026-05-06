<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index($slug)
    {
        $area = Area::where('key_name', $slug)->first();
        return view('principal.reservas', compact('slug','area'));
    }

    public function areas()
    {
        $areas = Area::all();
        return view('principal.areas', compact('areas'));
    }

    public function json($id)
    {
        $bookings = Booking::where('area_id', $id)->get();

        $data = [];

        foreach ($bookings as $row) {

            $data[] = [
                'title' => $row->title . ' | ' . $row->name,
                'start' => Carbon::parse($row->date)->format('Y-m-d').'T'.$row->start_time,
                'end' => Carbon::parse($row->date)->format('Y-m-d').'T'.$row->end_time,
                'backgroundColor' => $row->color,
                'borderColor' => $row->color,
                'textColor' => '#fff'
            ];
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'name'   => 'required|string|max:255',
            'obs'    => 'required|string',
        ], [
            'titulo.required' => 'La asignatura es obligatoria',
            'name.required'   => 'El nombre es obligatorio',
            'obs.required'    => 'El objetivo es obligatorio',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $inicio = Carbon::parse($request->inicio);
        $fin    = Carbon::parse($request->fin);

        Booking::create([
            'title' => $request->titulo,
            'area_id' => $request->espacio,
            'subject' => $request->obs,
            'name' => $request->name,

            'date' => $inicio->format('Y-m-d'),
            'start_time' => $inicio->format('H:i:s'),
            'end_time' => $fin->format('H:i:s'),

            'status' => 'Pendiente',
            'color' => '#F59E0B'
        ]);

        return response()->json(['ok'=>true]);
    }
}
