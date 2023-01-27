<?php

namespace App\Http\Controllers\InvoiceCharge\Imports;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;

use App\Http\Requests\Movement\ImportChargeRequest;

use App\Entities\Account,
    App\Entities\Subaccount,
    App\Entities\Order,
    App\Entities\Charge,
    App\Entities\OrderCharge,
    App\Entities\InvoiceCharge,
    App\Imports\ChargeImport;

use App\Events\MovementEvent as MEv;
use App\Exceptions\Account\InsufficientCreditException
        as AccountInsufficientCredit,
    App\Exceptions\Charge\DuplicatedChargeException
        as DuplicatedCharge,
    App\Exceptions\Order\InvalidStatusException
        as OrderInvalidStatus,
    App\Exceptions\Supplier\InvoicedLimitException;

class AllExtensionsController extends BaseImportController
{
    /**
     * @inheritDoc
     */
    protected function getUploadedSheet(Request $rq, $type)
    {
        //$sheets = Excel::toCollection(new ChargeImport, $rq->file('file'));
        $sheets = Excel::toArray(new ChargeImport, $rq->file('file'));
        $sheet  = $sheets[$this->getTypeSheet($type)-1];
        if ($type == InvoiceCharge::TYPE_CASH) {
            array_shift($sheet);
        }
        return $sheet;
    }

    /**
     * @inheritDoc
     */
    protected function getSheetErrors($sheet = [], $type)
    {
        $errors = [];
        foreach ($this->getTypeColumns($type) as $col) {
            if (!in_array($col, $sheet[0]))
                $errors[] = $col;
        }
        return $errors;
    }   

    /**
     * @inheritDoc
     */
    protected function getCharges($sheet = [], $type)
    {
        $collection = [];
        $cols = array_shift($sheet);

        foreach ($sheet as $row) {
            $parsed = [];
            $row    = array_combine($cols, $row);
            foreach ($this->getTypeColumns($type) as $key => $col) {
                $parsed[$key] = isset($row[$col]) ? $row[$col] : null;
            }
            $collection[] = $this->getCharge($parsed, $type);
        }
        return $collection;
    } 
}
