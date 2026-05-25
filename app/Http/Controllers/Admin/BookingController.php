<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Http\Requests\BookingRequest;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class BookingController extends Controller
{
    public function index()
    {

        abort_unless(Gate::allows('view.equipment') || Gate::allows('create.equipment'), 403);

        $actual_month = Carbon::now()->month;
        $actual_year  = Carbon::now()->year;

        $years = collect([]);

        $año_actual = Carbon::now()->year;

        for ($año = 2023; $año <= $actual_year; $año++) {
            $years[$año] = $año;
        }

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
            '12' => 'Diciembre'
        ]);


        $month = \Request('month') != null ? \Request('month') : $actual_month;
        $year  = \Request('year') != null ? \Request('year') : $actual_year;

        $search = request('search');
        $area_id = request('area_id') ? request('area_id'): 1;

        $areas = Area::pluck('name','id');
        $query = Booking::with('area')
        ->where('area_id', $area_id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('date','DESC');

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $bookings = $query->paginate(20)->appends(request()->all());

        $bookingsItems = collect($bookings->items())->map(function ($item) {

            $item->date_format = Carbon::parse($item->date)
                ->locale('es')
                ->translatedFormat('d M Y');

            $item->day_format = Carbon::parse($item->date)
                ->locale('es')
                ->translatedFormat('l j \\d\\e F \\d\\e\\l Y');

            $item->hour_range =
                Carbon::parse($item->start_time)->format('g:i A')
                . ' - ' .
                Carbon::parse($item->end_time)->format('g:i A');

            return $item;
        });        
        return view('admin.reservaciones.index', compact('bookings', 'bookingsItems', 'actual_month', 'actual_year', 'months', 'years','areas')); 
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('edit.equipment'), 403);
        $booking = Booking::find($id);

        return view('admin.reservaciones.editar', compact('booking'));
    }

    public function update(BookingRequest $request, $id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('edit.equipment'), 403);

        $booking = Booking::find($id);
        $status = $request->status;

        $color = match ($status) {
            'Pendiente' => '#F59E0B', // amarillo
            'Aprobado'  => '#10B981', // verde
            'Rechazado' => '#EF4444', // rojo
        };

        $booking->title = $request->title;
        $booking->subject = $request->subject;
        $booking->name = $request->name;
        $booking->status = $request->status;
        $booking->color = $color;
        $booking->save();

        alert('Se ha actualizado una reservación.');

        return response('', 204, [
            'Redirect-To' => url('admin/reservaciones/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('create.equipment'), 403);

        Booking::find($id)->delete();
        
        return response('', 204);

    }

     /*
    |--------------------------------------------------------------------------
    | EXPORT WORD
    |--------------------------------------------------------------------------
    */

    public function exportWord($month, $year, $area)
    {
        /*
        |--------------------------------------------------------------------------
        | OBTENER BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookings = Booking::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('area_id', $area)
            ->orderBy('date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TEMPLATE
        |--------------------------------------------------------------------------
        */

        $templatePath = storage_path(
            'app/templates/LAB-RG-06.docx'
        );

        $template = new TemplateProcessor($templatePath);

        /*
        |--------------------------------------------------------------------------
        | CLONAR FILAS
        |--------------------------------------------------------------------------
        |
        | En tu Word debe existir:
        |
        | ${fecha}
        | ${practica}
        | ${objetivo}
        | ${profesor}
        | ${asignatura}
        |
        */

        $template->cloneRow('fecha', $bookings->count());

        /*
        |--------------------------------------------------------------------------
        | LLENAR DATOS
        |--------------------------------------------------------------------------
        */

        foreach ($bookings as $index => $booking) {

            $row = $index + 1;

            $template->setValue(
                "fecha#{$row}",
                date('d/m/Y', strtotime($booking->date))
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

        /*
        |--------------------------------------------------------------------------
        | GENERAR ARCHIVO
        |--------------------------------------------------------------------------
        */

        $fileName = 'Programacion_Practicas.docx';

        $tempFile = storage_path($fileName);

        $template->saveAs($tempFile);

        /*
        |--------------------------------------------------------------------------
        | DESCARGAR
        |--------------------------------------------------------------------------
        */

        return response()->download(
            $tempFile,
            $fileName,
            [
                'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]
        )->deleteFileAfterSend(true);
    }

     /*
    |--------------------------------------------------------------------------
    | EXPORT BITACORA
    |--------------------------------------------------------------------------
    */
    public function exportBitacora($month, $year, $area)
    {
        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookings = Booking::with('area')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('area_id', $area)
            ->orderBy('date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | LABORATORIO
        |--------------------------------------------------------------------------
        */

        $area = Area::find($area);

        $labName = $area->key_name ?? 'LABORATORIO';

        /*
        |--------------------------------------------------------------------------
        | TEMPLATE
        |--------------------------------------------------------------------------
        */

        $templatePath = storage_path(
            'app/templates/LAB-RG-03.docx'
        );

        $template = new TemplateProcessor($templatePath);

        /*
        |--------------------------------------------------------------------------
        | DATOS GENERALES
        |--------------------------------------------------------------------------
        */

        $template->setValue(
            'laboratorio',
            $labName
        );

        $template->setValue(
            'periodo',
            $month . '/' . $year
        );

        /*
        |--------------------------------------------------------------------------
        | SI NO HAY BOOKINGS
        |--------------------------------------------------------------------------
        */

        if ($bookings->count() <= 0) {

            $template->cloneRow('fecha', 1);

            $template->setValue('fecha#1', '');
            $template->setValue('practica#1', '');
            $template->setValue('objetivo#1', '');
            $template->setValue('profesor#1', '');
            $template->setValue('asignatura#1', '');
            $template->setValue('area#1', '');
            $template->setValue('fecha_realizada#1', '');
            $template->setValue('observaciones#1', '');
        }
        else {

            /*
            |--------------------------------------------------------------------------
            | CLONAR FILAS
            |--------------------------------------------------------------------------
            */

            $template->cloneRow(
                'fecha',
                $bookings->count()
            );

            /*
            |--------------------------------------------------------------------------
            | LLENAR TABLA
            |--------------------------------------------------------------------------
            */

            foreach ($bookings as $index => $booking) {

                $row = $index + 1;

                /*
                |--------------------------------------------------------------------------
                | FECHA PROGRAMADA
                |--------------------------------------------------------------------------
                */

                $template->setValue(
                    "fecha#{$row}",
                    date(
                        'd/m/Y',
                        strtotime($booking->date)
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | PRACTICA
                |--------------------------------------------------------------------------
                */

                $template->setValue(
                    "practica#{$row}",
                    $booking->title ?? ''
                );

                /*
                |--------------------------------------------------------------------------
                | OBJETIVO
                |--------------------------------------------------------------------------
                */

                $template->setValue(
                    "objetivo#{$row}",
                    $booking->subject ?? ''
                );

                /*
                |--------------------------------------------------------------------------
                | PROFESOR
                |--------------------------------------------------------------------------
                */

                $template->setValue(
                    "profesor#{$row}",
                    $booking->name ?? ''
                );

                /*
                |--------------------------------------------------------------------------
                | ASIGNATURA
                |--------------------------------------------------------------------------
                */

                $template->setValue(
                    "asignatura#{$row}",
                    $booking->asignature ?? ''
                );

                /*
                |--------------------------------------------------------------------------
                | AREA
                |--------------------------------------------------------------------------
                */

                $template->setValue(
                    "area#{$row}",
                    $labName
                );

                /*
                |--------------------------------------------------------------------------
                | FECHA REALIZADA
                |--------------------------------------------------------------------------
                */

                $template->setValue(
                    "fecha_realizada#{$row}",
                    date(
                        'd/m/Y',
                        strtotime($booking->date)
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | OBSERVACIONES
                |--------------------------------------------------------------------------
                */

                $template->setValue(
                    "observaciones#{$row}",
                    $booking->observations ?? ''
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ARCHIVO
        |--------------------------------------------------------------------------
        */

        $fileName = 'Bitacora_Laboratorio.docx';

        $tempFile = storage_path($fileName);

        $template->saveAs($tempFile);

        /*
        |--------------------------------------------------------------------------
        | DESCARGAR
        |--------------------------------------------------------------------------
        */

        return response()->download(
            $tempFile,
            $fileName
        )->deleteFileAfterSend(true);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportExcel($month, $year, $area)
    {
        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookings = Booking::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('area_id', $area)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TEMPLATE
        |--------------------------------------------------------------------------
        */

        $templatePath = storage_path(
            'app/templates/LAB-RG-01.xlsx'
        );

        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();

        /*
        |--------------------------------------------------------------------------
        | COLUMNAS POR DIA
        |--------------------------------------------------------------------------
        */

        $days = [
            'Monday'    => 'B',
            'Tuesday'   => 'C',
            'Wednesday' => 'D',
            'Thursday'  => 'E',
            'Friday'    => 'F',
        ];

        /*
        |--------------------------------------------------------------------------
        | FILAS POR HORA
        |--------------------------------------------------------------------------
        */

        $hours = [
            '07:00:00' => 7,
            '08:00:00' => 8,
            '09:00:00' => 9,
            '10:00:00' => 10,
            '11:00:00' => 11,
            '12:00:00' => 12,
            '13:00:00' => 13,
            '14:00:00' => 14,
            '15:00:00' => 15,
            '16:00:00' => 16,
            '17:00:00' => 17,
            '18:00:00' => 18,
            '19:00:00' => 19,
        ];

        /*
        |--------------------------------------------------------------------------
        | LLENAR CALENDARIO
        |--------------------------------------------------------------------------
        */

        foreach ($bookings as $booking) {

            $dayName = date('l', strtotime($booking->date));

            if (!isset($days[$dayName])) {
                continue;
            }

            $column = $days[$dayName];

            $startTime = date(
                'H:i:s',
                strtotime($booking->start_time)
            );

            if (!isset($hours[$startTime])) {
                continue;
            }

            $row = $hours[$startTime];

            $cell = $column . $row;

            /*
            |--------------------------------------------------------------------------
            | TEXTO
            |--------------------------------------------------------------------------
            */

            $text =
                $booking->title . "\n" .
                $booking->name;

            $sheet->setCellValue($cell, $text);

            /*
            |--------------------------------------------------------------------------
            | AJUSTE TEXTO
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle($cell)
                ->getAlignment()
                ->setWrapText(true);
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR
        |--------------------------------------------------------------------------
        */

        $fileName = 'Agenda_Practicas.xlsx';

        $tempFile = storage_path($fileName);

        $writer = new Xlsx($spreadsheet);

        $writer->save($tempFile);

        /*
        |--------------------------------------------------------------------------
        | DESCARGAR
        |--------------------------------------------------------------------------
        */

        return response()->download(
            $tempFile,
            $fileName
        )->deleteFileAfterSend(true);
    }
}
