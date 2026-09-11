<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\BookingRequest;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        abort_unless(
            Gate::allows('view.equipment') ||
            Gate::allows('create.equipment'),
            403
        );

        $actual_month = Carbon::now()->month;
        $actual_year = Carbon::now()->year;

        /*
        |--------------------------------------------------------------------------
        | AÑOS
        |--------------------------------------------------------------------------
        */

        $years = collect([]);

        $año_actual = Carbon::now()->year;

        for (
            $año = 2023;
            $año <= $actual_year;
            $año++
        ) {
            $years[$año] = $año;
        }


        /*
        |--------------------------------------------------------------------------
        | MESES
        |--------------------------------------------------------------------------
        */

        $months = collect([
            '1' => 'Enero',
            '2' => 'Febrero',
            '3' => 'Marzo',
            '4' => 'Abril',
            '5' => 'Mayo',
            '6' => 'Junio',
            '7' => 'Julio',
            '8' => 'Agosto',
            '9' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ]);


        /*
        |--------------------------------------------------------------------------
        | FILTROS
        |--------------------------------------------------------------------------
        */

        $month =
            request('month') !== null
                ? request('month')
                : $actual_month;

        $year =
            request('year') !== null
                ? request('year')
                : $actual_year;

        $search =
            request('search');

        $area_id =
            request('area_id')
                ? request('area_id')
                : 1;

        $status =
            request('status');


        /*
        |--------------------------------------------------------------------------
        | AREAS
        |--------------------------------------------------------------------------
        */

        $areas =
            Area::pluck(
                'name',
                'id'
            );


        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $query =
            Booking::with('area')
                ->where(
                    'area_id',
                    $area_id
                )
                ->whereMonth(
                    'created_at',
                    $month
                )
                ->whereYear(
                    'created_at',
                    $year
                )
                ->orderBy(
                    'date',
                    'DESC'
                );


        /*
        |--------------------------------------------------------------------------
        | BUSQUEDA
        |--------------------------------------------------------------------------
        */

        if ($search) {

            $query->where(
                'name',
                'LIKE',
                "%{$search}%"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO ESTADO
        |--------------------------------------------------------------------------
        */

        if ($status) {

            $query->where(
                'status',
                $status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINACION
        |--------------------------------------------------------------------------
        */

        $bookings =
            $query
                ->paginate(20)
                ->appends(
                    request()->all()
                );


        /*
        |--------------------------------------------------------------------------
        | DATOS PARA VUE
        |--------------------------------------------------------------------------
        */

        $bookingsItems =
            collect(
                $bookings->items()
            )->map(
                function ($item) {

                    $item->date_format =
                        Carbon::parse(
                            $item->date
                        )
                        ->locale('es')
                        ->translatedFormat(
                            'd M Y'
                        );


                    $item->day_format =
                        Carbon::parse(
                            $item->date
                        )
                        ->locale('es')
                        ->translatedFormat(
                            'l j \d\e F \d\e\l Y'
                        );


                    $item->hour_range =
                        Carbon::parse(
                            $item->start_time
                        )->format('g:i A')
                        . ' - ' .
                        Carbon::parse(
                            $item->end_time
                        )->format('g:i A');


                    return $item;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | VISTA
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reservaciones.index',
            compact(
                'bookings',
                'bookingsItems',
                'actual_month',
                'actual_year',
                'months',
                'years',
                'areas'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR RESERVACIONES
    |--------------------------------------------------------------------------
    |
    | Puede recibir:
    |
    | ids = [1,2,3]
    |
    | o:
    |
    | all_pending = true
    |
    | En el segundo caso toma TODOS los pendientes
    | respetando los filtros enviados.
    |
    |--------------------------------------------------------------------------
    */

    public function validar(Request $request)
    {
        abort_unless(
            Gate::allows('view.equipment') ||
            Gate::allows('edit.equipment'),
            403
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDACION
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'integer|exists:bookings,id',

            'all_pending' => 'nullable|boolean',

            'month' => 'nullable|integer|between:1,12',

            'year' => 'nullable|integer',

            'area_id' => 'nullable|integer|exists:areas,id',

            'search' => 'nullable|string',

            'status' => 'nullable|in:Pendiente,Aprobado,Rechazado',
        ]);


        $ids =
            $request->input(
                'ids',
                []
            );


        $allPending =
            $request->boolean(
                'all_pending'
            );


        /*
        |--------------------------------------------------------------------------
        | QUERY BASE
        |--------------------------------------------------------------------------
        */

        $query =
            Booking::query()
                ->where(
                    'status',
                    'Pendiente'
                );


        /*
        |--------------------------------------------------------------------------
        | VALIDAR SELECCIONADAS
        |--------------------------------------------------------------------------
        */

        if (
            !$allPending &&
            !empty($ids)
        ) {

            $query->whereIn(
                'id',
                $ids
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR TODOS LOS PENDIENTES
        |--------------------------------------------------------------------------
        */

        if ($allPending) {

            /*
            |--------------------------------------------------------------------------
            | AREA
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'area_id'
                )
            ) {

                $query->where(
                    'area_id',
                    $request->area_id
                );
            }


            /*
            |--------------------------------------------------------------------------
            | MES
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'month'
                )
            ) {

                $query->whereMonth(
                    'created_at',
                    $request->month
                );
            }


            /*
            |--------------------------------------------------------------------------
            | AÑO
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'year'
                )
            ) {

                $query->whereYear(
                    'created_at',
                    $request->year
                );
            }


            /*
            |--------------------------------------------------------------------------
            | BUSQUEDA
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'search'
                )
            ) {

                $search =
                    $request->search;

                $query->where(
                    'name',
                    'LIKE',
                    "%{$search}%"
                );
            }


            /*
            |--------------------------------------------------------------------------
            | ESTADO
            |--------------------------------------------------------------------------
            |
            | Aunque el filtro sea Aprobado o Rechazado,
            | aquí solamente existen pendientes.
            |
            | Por eso no se utiliza para modificar la consulta.
            |
            |--------------------------------------------------------------------------
            */
        }


        /*
        |--------------------------------------------------------------------------
        | OBTENER RESERVACIONES
        |--------------------------------------------------------------------------
        */

        $bookings =
            $query->get();


        /*
        |--------------------------------------------------------------------------
        | NINGUNA RESERVACION
        |--------------------------------------------------------------------------
        */

        if (
            $bookings->isEmpty()
        ) {

            return response()->json([
                'ok' => false,

                'message' =>
                    'No se encontraron reservaciones pendientes para validar.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | APROBAR
        |--------------------------------------------------------------------------
        */

        $cantidad = 0;


        foreach (
            $bookings as $booking
        ) {

            $booking->status =
                'Aprobado';

            $booking->color =
                '#10B981';

            $booking->save();

            $cantidad++;
        }


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'ok' => true,

            'cantidad' =>
                $cantidad,

            'message' =>
                $cantidad === 1
                    ? 'Se validó correctamente 1 reservación.'
                    : "Se validaron correctamente {$cantidad} reservaciones.",
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        abort_unless(
            Gate::allows('view.equipment') ||
            Gate::allows('edit.equipment'),
            403
        );

        $booking =
            Booking::findOrFail(
                $id
            );

        return view(
            'admin.reservaciones.editar',
            compact('booking')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        BookingRequest $request,
        $id
    ) {

        abort_unless(
            Gate::allows('view.equipment') ||
            Gate::allows('edit.equipment'),
            403
        );


        $booking =
            Booking::findOrFail(
                $id
            );


        $status =
            $request->status;


        /*
        |--------------------------------------------------------------------------
        | COLOR SEGUN ESTADO
        |--------------------------------------------------------------------------
        */

        $color =
            match ($status) {

                'Pendiente' =>
                    '#F59E0B',

                'Aprobado' =>
                    '#10B981',

                'Rechazado' =>
                    '#EF4444',

                default =>
                    '#F59E0B',
            };


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR
        |--------------------------------------------------------------------------
        */

        $booking->title =
            $request->title;

        $booking->subject =
            $request->subject;

        $booking->name =
            $request->name;

        $booking->status =
            $request->status;

        $booking->color =
            $color;


        $booking->save();


        alert(
            'Se ha actualizado una reservación.'
        );


        return response(
            '',
            204,
            [
                'Redirect-To' =>
                    url(
                        'admin/reservaciones/'
                    ),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        abort_unless(
            Gate::allows('view.equipment') ||
            Gate::allows('create.equipment'),
            403
        );


        Booking::findOrFail(
            $id
        )->delete();


        return response(
            '',
            204
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT WORD
    |--------------------------------------------------------------------------
    */

    public function exportWord(
        $month,
        $year,
        $area
    ) {

        $bookings =
            Booking::whereMonth(
                'date',
                $month
            )
            ->whereYear(
                'date',
                $year
            )
            ->where(
                'area_id',
                $area
            )
            ->orderBy(
                'date'
            )
            ->get();


        $templatePath =
            storage_path(
                'app/templates/LAB-RG-06.docx'
            );


        $template =
            new TemplateProcessor(
                $templatePath
            );


        $template->cloneRow(
            'fecha',
            max(
                $bookings->count(),
                1
            )
        );


        if (
            $bookings->count() === 0
        ) {

            $template->setValue(
                'fecha#1',
                ''
            );

            $template->setValue(
                'practica#1',
                ''
            );

            $template->setValue(
                'objetivo#1',
                ''
            );

            $template->setValue(
                'profesor#1',
                ''
            );

            $template->setValue(
                'asignatura#1',
                ''
            );

        } else {

            foreach (
                $bookings as $index => $booking
            ) {

                $row =
                    $index + 1;


                $template->setValue(
                    "fecha#{$row}",
                    date(
                        'd/m/Y',
                        strtotime(
                            $booking->date
                        )
                    )
                );


                $template->setValue(
                    "practica#{$row}",
                    $booking->title ?? ''
                );


                $template->setValue(
                    "objetivo#{$row}",
                    $booking->subject ?? ''
                );


                $template->setValue(
                    "profesor#{$row}",
                    $booking->name ?? ''
                );


                $template->setValue(
                    "asignatura#{$row}",
                    $booking->asignature ?? ''
                );
            }
        }


        $fileName =
            'Programacion_Practicas.docx';


        $tempFile =
            storage_path(
                $fileName
            );


        $template->saveAs(
            $tempFile
        );


        return response()
            ->download(
                $tempFile,
                $fileName,
                [
                    'Content-Type' =>
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ]
            )
            ->deleteFileAfterSend(
                true
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT BITACORA
    |--------------------------------------------------------------------------
    */

    public function exportBitacora(
        $month,
        $year,
        $area
    ) {

        $bookings =
            Booking::with('area')
                ->whereMonth(
                    'date',
                    $month
                )
                ->whereYear(
                    'date',
                    $year
                )
                ->where(
                    'area_id',
                    $area
                )
                ->orderBy(
                    'date'
                )
                ->get();


        $areaModel =
            Area::find(
                $area
            );


        $labName =
            $areaModel->key_name
            ?? 'LABORATORIO';


        $templatePath =
            storage_path(
                'app/templates/LAB-RG-03.docx'
            );


        $template =
            new TemplateProcessor(
                $templatePath
            );


        $template->setValue(
            'laboratorio',
            $labName
        );


        $template->setValue(
            'periodo',
            $month . '/' . $year
        );


        if (
            $bookings->count() <= 0
        ) {

            $template->cloneRow(
                'fecha',
                1
            );


            $template->setValue(
                'fecha#1',
                ''
            );

            $template->setValue(
                'practica#1',
                ''
            );

            $template->setValue(
                'objetivo#1',
                ''
            );

            $template->setValue(
                'profesor#1',
                ''
            );

            $template->setValue(
                'asignatura#1',
                ''
            );

            $template->setValue(
                'area#1',
                ''
            );

            $template->setValue(
                'fecha_realizada#1',
                ''
            );

            $template->setValue(
                'observaciones#1',
                ''
            );

        } else {

            $template->cloneRow(
                'fecha',
                $bookings->count()
            );


            foreach (
                $bookings as $index => $booking
            ) {

                $row =
                    $index + 1;


                $template->setValue(
                    "fecha#{$row}",
                    date(
                        'd/m/Y',
                        strtotime(
                            $booking->date
                        )
                    )
                );


                $template->setValue(
                    "practica#{$row}",
                    $booking->title ?? ''
                );


                $template->setValue(
                    "objetivo#{$row}",
                    $booking->subject ?? ''
                );


                $template->setValue(
                    "profesor#{$row}",
                    $booking->name ?? ''
                );


                $template->setValue(
                    "asignatura#{$row}",
                    $booking->asignature ?? ''
                );


                $template->setValue(
                    "area#{$row}",
                    $labName
                );


                $template->setValue(
                    "fecha_realizada#{$row}",
                    date(
                        'd/m/Y',
                        strtotime(
                            $booking->date
                        )
                    )
                );


                $template->setValue(
                    "observaciones#{$row}",
                    $booking->observations ?? ''
                );
            }
        }


        $fileName =
            'Bitacora_Laboratorio.docx';


        $tempFile =
            storage_path(
                $fileName
            );


        $template->saveAs(
            $tempFile
        );


        return response()
            ->download(
                $tempFile,
                $fileName
            )
            ->deleteFileAfterSend(
                true
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportExcel(
        $month,
        $year,
        $area
    ) {

        $bookings =
            Booking::whereMonth(
                'date',
                $month
            )
            ->whereYear(
                'date',
                $year
            )
            ->where(
                'area_id',
                $area
            )
            ->get();


        $templatePath =
            storage_path(
                'app/templates/LAB-RG-01.xlsx'
            );


        $spreadsheet =
            IOFactory::load(
                $templatePath
            );


        $sheet =
            $spreadsheet
                ->getActiveSheet();


        $days = [

            'Monday' =>
                'B',

            'Tuesday' =>
                'C',

            'Wednesday' =>
                'D',

            'Thursday' =>
                'E',

            'Friday' =>
                'F',
        ];


        $hours = [

            '07:00:00' =>
                7,

            '08:00:00' =>
                8,

            '09:00:00' =>
                9,

            '10:00:00' =>
                10,

            '11:00:00' =>
                11,

            '12:00:00' =>
                12,

            '13:00:00' =>
                13,

            '14:00:00' =>
                14,

            '15:00:00' =>
                15,

            '16:00:00' =>
                16,

            '17:00:00' =>
                17,

            '18:00:00' =>
                18,

            '19:00:00' =>
                19,
        ];


        foreach (
            $bookings as $booking
        ) {

            $dayName =
                date(
                    'l',
                    strtotime(
                        $booking->date
                    )
                );


            if (
                !isset(
                    $days[$dayName]
                )
            ) {
                continue;
            }


            $column =
                $days[$dayName];


            $startTime =
                date(
                    'H:i:s',
                    strtotime(
                        $booking->start_time
                    )
                );


            if (
                !isset(
                    $hours[$startTime]
                )
            ) {
                continue;
            }


            $row =
                $hours[$startTime];


            $cell =
                $column . $row;


            $text =
                $booking->title
                . "\n"
                . $booking->name;


            $sheet->setCellValue(
                $cell,
                $text
            );


            $sheet
                ->getStyle($cell)
                ->getAlignment()
                ->setWrapText(
                    true
                );
        }


        $fileName =
            'Agenda_Practicas.xlsx';


        $tempFile =
            storage_path(
                $fileName
            );


        $writer =
            new Xlsx(
                $spreadsheet
            );


        $writer->save(
            $tempFile
        );


        return response()
            ->download(
                $tempFile,
                $fileName
            )
            ->deleteFileAfterSend(
                true
            );
    }
}