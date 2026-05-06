<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DamageReport;
use Illuminate\Support\Facades\Gate;

class DamageReportController extends Controller
{
    public function index()
    {

        abort_unless(Gate::allows('view.equipment') || Gate::allows('create.equipment'), 403);
        $search = request('search');

        $query = DamageReport::orderBy('created_at','DESC');

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $damagereports = $query->paginate(20)->appends(request()->all());
        $damagereportsItems = collect($damagereports->items());
        return view('admin.reportes.index', compact('damagereports', 'damagereportsItems')); 
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('edit.equipment'), 403);
        $damagereport = DamageReport::find($id);

        return view('admin.reportes.editar', compact('damagereport'));
    }

    public function update(Request $request, $id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('edit.equipment'), 403);

        $damagereports = DamageReport::find($id);
        $status = $request->status;

       $color = match ($status) {
            'Pendiente'   => '#F59E0B', // amarillo
            'En proceso'  => '#3B82F6', // azul
            'Resuelto'    => '#10B981', // verde
            'Cancelado'   => '#EF4444', // rojo
        };
        $damagereports->status = $request->status;
        $damagereports->color = $color;
        $damagereports->save();

        alert('Se ha actualizado un reporte.');

        return response('', 204, [
            'Redirect-To' => url('admin/reportes/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.equipment') || Gate::allows('create.equipment'), 403);

        DamageReport::find($id)->delete();
        
        return response('', 204);

    }
}
