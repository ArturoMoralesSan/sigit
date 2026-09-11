<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index($slug)
    {
        $area = Area::where('key_name', $slug)->first();

        return view('principal.reservas', compact('slug', 'area'));
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
            $date = Carbon::parse($row->date)->format('Y-m-d');

            $data[] = [
                'title' => $row->asignature . ' | ' . $row->name,
                'start' => $date . 'T' . $row->start_time,
                'end' => $date . 'T' . $row->end_time,
                'backgroundColor' => $row->color,
                'borderColor' => $row->color,
                'textColor' => '#fff',
            ];
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'titulo' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'obs' => 'required|string',
                'asignatura' => 'required|string',

                'inicio' => 'required|date',
                'fin' => 'required|date',

                'espacio' => 'required|integer',

                'recurrencia' => [
                    'required',
                    'in:once,1week,2weeks,1month,2months,3months,4months',
                ],

                'dias' => 'nullable|array',
                'dias.*' => 'integer|between:1,5',
            ],
            [
                'titulo.required' => 'La práctica es obligatoria.',
                'name.required' => 'El nombre es obligatorio.',
                'obs.required' => 'El objetivo es obligatorio.',
                'asignatura.required' => 'La asignatura es obligatoria.',
                'inicio.required' => 'La fecha y hora de inicio son obligatorias.',
                'fin.required' => 'La fecha y hora de término son obligatorias.',
                'recurrencia.required' => 'Debes seleccionar una frecuencia.',
                'recurrencia.in' => 'La frecuencia seleccionada no es válida.',
                'dias.required' => 'Debes seleccionar al menos un día.',
                'dias.array' => 'Los días seleccionados no son válidos.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $inicio = Carbon::parse($request->inicio);
        $fin = Carbon::parse($request->fin);
        $hoy = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | VALIDAR FECHA
        |--------------------------------------------------------------------------
        */

        if ($inicio->copy()->startOfDay()->lessThan($hoy)) {
            return response()->json([
                'ok' => false,
                'errors' => [
                    'inicio' => [
                        'No puedes realizar una reservación en una fecha anterior al día de hoy.',
                    ],
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAR HORARIO
        |--------------------------------------------------------------------------
        */

        if ($fin->lessThanOrEqualTo($inicio)) {
            return response()->json([
                'ok' => false,
                'errors' => [
                    'fin' => [
                        'La hora de término debe ser posterior a la hora de inicio.',
                    ],
                ],
            ], 422);
        }

        $startTime = $inicio->format('H:i:s');
        $endTime = $fin->format('H:i:s');

        /*
        |--------------------------------------------------------------------------
        | GENERAR FECHAS
        |--------------------------------------------------------------------------
        */

        $fechas = [];

        /*
        |--------------------------------------------------------------------------
        | UNA SOLA VEZ
        |--------------------------------------------------------------------------
        */

        if ($request->recurrencia === 'once') {
            $fechas[] = $inicio->copy()->startOfDay();
        } else {

            $fechaInicio = $inicio->copy()->startOfDay();

            if ($fechaInicio->lessThan($hoy)) {
                $fechaInicio = $hoy->copy();
            }

            $dias = $request->input('dias', []);

            if (empty($dias)) {
                return response()->json([
                    'ok' => false,
                    'errors' => [
                        'dias' => [
                            'Selecciona al menos un día de la semana.',
                        ],
                    ],
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | CALCULAR FECHA FINAL
            |--------------------------------------------------------------------------
            */

            switch ($request->recurrencia) {

                case '1week':
                    $fechaFin = $fechaInicio
                        ->copy()
                        ->addWeek()
                        ->subDay();
                    break;

                case '2weeks':
                    $fechaFin = $fechaInicio
                        ->copy()
                        ->addWeeks(2)
                        ->subDay();
                    break;

                case '1month':
                    $fechaFin = $fechaInicio
                        ->copy()
                        ->addMonth()
                        ->subDay();
                    break;

                case '2months':
                    $fechaFin = $fechaInicio
                        ->copy()
                        ->addMonths(2)
                        ->subDay();
                    break;

                case '3months':
                    $fechaFin = $fechaInicio
                        ->copy()
                        ->addMonths(3)
                        ->subDay();
                    break;

                case '4months':
                    $fechaFin = $fechaInicio
                        ->copy()
                        ->addMonths(4)
                        ->subDay();
                    break;

                default:
                    $fechaFin = $fechaInicio->copy();
                    break;
            }

            /*
            |--------------------------------------------------------------------------
            | RECORRER FECHAS
            |--------------------------------------------------------------------------
            */

            $fecha = $fechaInicio->copy();

            while ($fecha->lessThanOrEqualTo($fechaFin)) {

                if (
                    in_array(
                        $fecha->dayOfWeekIso,
                        $dias
                    )
                ) {
                    if ($fecha->greaterThanOrEqualTo($hoy)) {
                        $fechas[] = $fecha->copy();
                    }
                }

                $fecha->addDay();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ELIMINAR FECHAS DUPLICADAS
        |--------------------------------------------------------------------------
        */

        $fechasUnicas = [];

        foreach ($fechas as $fecha) {
            $key = $fecha->format('Y-m-d');

            $fechasUnicas[$key] = $fecha;
        }

        ksort($fechasUnicas);

        $fechas = array_values($fechasUnicas);

        /*
        |--------------------------------------------------------------------------
        | VALIDAR QUE EXISTAN FECHAS
        |--------------------------------------------------------------------------
        */

        if (empty($fechas)) {
            return response()->json([
                'ok' => false,
                'errors' => [
                    'dias' => [
                        'No se encontraron fechas válidas para la reservación.',
                    ],
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | BUSCAR CONFLICTOS
        |--------------------------------------------------------------------------
        */

        $conflictos = [];

        foreach ($fechas as $fecha) {

            $date = $fecha->format('Y-m-d');

            $existe = Booking::where(
                    'area_id',
                    $request->espacio
                )
                ->whereDate('date', $date)
                ->where(
                    'start_time',
                    '<',
                    $endTime
                )
                ->where(
                    'end_time',
                    '>',
                    $startTime
                )
                ->exists();

            if ($existe) {
                $conflictos[] =
                    $fecha->translatedFormat(
                        'l d \d\e F \d\e Y'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SI HAY CONFLICTOS
        |--------------------------------------------------------------------------
        */

        if (!empty($conflictos)) {
            return response()->json([
                'ok' => false,
                'conflictos' => $conflictos,
                'message' =>
                    'No se puede realizar la reservación porque existen fechas y horarios ocupados.',
            ], 409);
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR RESERVACIONES
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $fechas,
            $request,
            $startTime,
            $endTime
        ) {

            foreach ($fechas as $fecha) {

                Booking::create([
                    'title' => $request->titulo,
                    'area_id' => $request->espacio,
                    'subject' => $request->obs,
                    'name' => $request->name,
                    'asignature' => $request->asignatura,
                    'date' => $fecha->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 'Pendiente',
                    'color' => '#F59E0B',
                ]);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'ok' => true,
            'cantidad' => count($fechas),
            'message' => count($fechas) === 1
                ? 'La reservación se creó correctamente.'
                : 'Se crearon ' . count($fechas) . ' reservaciones correctamente.',
        ]);
    }
}