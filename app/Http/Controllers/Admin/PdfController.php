<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Voucher;

class PdfController extends Controller
{
    public function pdfVoucher($id)
    {
        $voucher = Voucher::find($id);
        
        $pdf = PDF::loadView('admin.pdf.voucher', compact('voucher'));
        $pdf->setPaper('letter', 'portrait'); 
        return $pdf->stream('voucher.pdf',['Attachment' => false]);
        //return $pdf->download('reporte-ingresos-sucursal.pdf');   
    }
}
