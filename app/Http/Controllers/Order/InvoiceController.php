<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\BaseController;
use App\Entities\Order;

class InvoiceController extends BaseController
{
    //
    public function create(Order $order)
    {
        $pdf = PDF::loadView('pdf.invoice', [
            'entity' => $order,
        ]);
        return $pdf->stream();
        return $pdf->download('inv.pdf');
    }
}
