<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use App\Models\Voucher;
use App\Models\Equipment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('create.voucher'), 403);

        $search = request('search');

        $query = Voucher::orderBy('req','ASC');

        if ($search) {
            $query->where('req', 'LIKE', "%$search%");
        }

        $vouchers = $query->paginate(20)->appends(request()->all());
        $vouchersItems =Collect($vouchers->items());

        return view('admin.vales.index', compact('vouchers', 'vouchersItems'));
    }

    public function create()
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('create.voucher'), 403);
        
        $equipments = collect(Equipment::all(['id', 'product', 'control_tag'])->mapWithKeys(function ($item) {
            return [$item['id'] => $item['product'] . ' (' . $item['control_tag'].')'];
        })->toArray());

        return view('admin.vales.crear', compact('equipments'));
    }

    public function save(VoucherRequest $request)
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('edit.voucher'), 403);
        
        if ($request->voucher_id == null) {
            $voucher = new Voucher;
        } else {
            $voucher = Voucher::find($request->voucher_id);
        }
        
        $voucher->req = $request->req;
        $voucher->teacher = $request->teacher;
        $voucher->group = $request->group;
        $voucher->subject = $request->subject;
        $voucher->laboratory = $request->laboratory;
        $voucher->return_date = $request->return_date;
        $voucher->status = $request->status;
        $voucher->save();

        $voucher->equipments()->detach();
        for($i = 1; $i <= $request->product_count; $i++){
            $voucher->equipments()->attach($request['product'.$i.'_producto'], ['quantity'=> $request['product'.$i.'_quantity']]);
        }

        if ($request->voucher_id == null) {
            alert('Se ha agregado un vale de material.');
        } else {
            alert('Se ha actualizado un vale de material.');
        }
        

        return response('', 204, [
            'Redirect-To' => url('admin/vales-equipo')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('edit.voucher'), 403);
        
        $voucher = Voucher::find($id);
        $assigned_products = $voucher->equipments()->get();
        $equipments = collect(Equipment::all(['id', 'product', 'control_tag'])->mapWithKeys(function ($item) {
            return [$item['id'] => $item['product'] . ' (' . $item['control_tag'].')'];
        })->toArray());

        return view('admin.vales.editar', compact('voucher','equipments','assigned_products'));
    }


    public function update(VoucherRequest $request, $id)
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('edit.voucher'), 403);

        $voucher = Voucher::find($id);

        $voucher->subject = $request->subject;
        $voucher->laboratory = $request->laboratory;
        $voucher->return_date = $request->return_date;
        $voucher->status = $request->status;
        
        $voucher->save();

        alert('Se ha actualizado un vale de material.');

        return response('', 204, [
            'Redirect-To' => url('admin/vale/')
        ]);
    }

    public function delete($id)
    {
        abort_unless(Gate::allows('view.voucher') || Gate::allows('create.voucher'), 403);

        Voucher::find($id)->delete();
        
        return response('', 204);

    }
}
