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


class BookingController extends Controller
{
    public function index()
    {

        abort_unless(Gate::allows('view.equipment') || Gate::allows('create.equipment'), 403);
        $search = request('search');

        $query = Booking::with('area')->orderBy('date','DESC');

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
        return view('admin.reservaciones.index', compact('bookings', 'bookingsItems')); 
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
}
