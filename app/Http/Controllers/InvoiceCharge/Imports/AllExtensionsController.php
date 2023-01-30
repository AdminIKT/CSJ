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
        $sheet  = $sheets[self::getTypeSheet($type)-1];
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
        foreach (self::getTypeColumns($type) as $col) {
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

        // Avoid file duplicated column names as C_COL_HZENTRY
        $uniqueCols     = array_unique($cols);
        $duplicatedCols = array_diff_assoc($cols, $uniqueCols);

        foreach ($sheet as $row) {
            $parsed = [];
            $row    = array_diff_key($row, $duplicatedCols);
            $row    = array_combine($uniqueCols, $row);
            foreach (self::getTypeColumns($type) as $key => $col) {
                $parsed[$key] = isset($row[$col]) ? $row[$col] : null;
            }
            $collection[] = $this->getCharge($parsed, $type);
        }
        return $collection;
    } 

    /**
     * @inheritDoc
     */
    public static function getSupportedMimeTypes()
    {
        return ['xlsx', 'xlsm', 'xltx', 'xltm', 'xls', 'xlt'];
    } 
}
