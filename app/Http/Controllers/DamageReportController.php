<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DamageReport; 

class DamageReportController extends Controller
{
    public function create()
    {
        return view('principal.reporte');
    }

    public function store(Request $request)
    {
        try {

            $year = date('Y');

            $last = DamageReport::whereYear('created_at', $year)
                        ->orderBy('id','desc')
                        ->first();

            $number = $last ? (int) substr($last->folio, -4) + 1 : 1;

            $folio = 'RPT-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            $report = new DamageReport;

            $report->folio = $folio;
            $report->name = $request->name;
            $report->area = $request->area;
            $report->build = $request->build;
            $report->type = $request->type;
            $report->item_name = $request->item_name;
            $report->priority = $request->priority;
            $report->problem = $request->problem;
            $report->description = $request->description;

            if($request->hasFile('photo')){
                $report->photo = $request->file('photo')->store('reportes','public');
            }

            $report->status = 'Pendiente';
            $report->color = '#F59E0B';

            $report->save();

            return response()->json([
                'success' => true,
                'message' => 'Reporte enviado correctamente',
                'folio' => $folio
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar'
            ], 500);

        }
    }

    public function consulta()
    {
        return view('principal.consulta');
    }

    public function buscar(Request $request)
    {
        $reporte = DamageReport::where('folio', $request->folio)->first();

        if(!$reporte){
            return response()->json([
                'message' => 'No encontrado'
            ], 404);
        }

        return response()->json($reporte);
    }
}
