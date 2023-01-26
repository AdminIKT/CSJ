<?php

namespace App\Http\Controllers\InvoiceCharge\Imports;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as Ex;

use App\Http\Controllers\InvoiceChargeController,
    App\Http\Requests\Movement\ImportChargeRequest;

use App\Entities\Account,
    App\Entities\Subaccount,
    App\Entities\Order,
    App\Entities\Charge,
    App\Entities\OrderCharge,
    App\Entities\InvoiceCharge;

use App\Events\MovementEvent as MEv;
use App\Exceptions\Account\InsufficientCreditException
        as AccountInsufficientCredit,
    App\Exceptions\Charge\DuplicatedChargeException
        as DuplicatedCharge,
    App\Exceptions\Order\InvalidStatusException
        as OrderInvalidStatus,
    App\Exceptions\Supplier\InvoicedLimitException;

/**
 * @deprecate Only accepts .xlsx extensions, 
 * use AllExtensionsController instead 
 */
class BaseImportController extends InvoiceChargeController
{
    const I_COL_SEQUENCE      = "DESCRIPCION";
    const I_COL_CREDIT        = "IMPORTE";
    const I_COL_INVOICE       = "NUM FACTURA";
    const I_COL_INVOICEDATE   = "F FACTURA";
    const I_COL_HZYEAR        = "EJERCICIO";
    const I_COL_HZENTRY       = "ASIENTO REAL";
    const C_COL_SEQUENCE      = "Descripción";
    const C_COL_CREDIT        = "Importe";
    const C_COL_INVOICE       = "Nº factura";
    const C_COL_INVOICEDATE   = "Fecha de factura";
    const C_COL_HZYEAR        = "Ejercicio";
    const C_COL_HZENTRY       = "Corr.";

    const SESSION_FILE_DATA     = "parsed-inv-charges";
    const FILE_INVOICED_SHEET   = 1;
    const FILE_CASH_SHEET       = 1;

    /**
     * @return \Illuminate\Http\Response
     */
    public function createStep1(Request $rq)
    {
        $rq->session()->forget(static::SESSION_FILE_DATA);
        
        return view('movements.import.step1', [
            'route' => 'imports.store.step1',
            'type'  => $rq->get('charge', InvoiceCharge::TYPE_CASH),
        ]); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(Request $rq)
    {
        $data = $rq->validate([
            'file' => 'required|mimes:xlsx,xlsm,xltx,xltm,xls,xlt',
        ]);
        $type = $rq->get('charge', InvoiceCharge::TYPE_CASH);
        if (empty($rq->session()->get(static::SESSION_FILE_DATA))) {
            $sheet  = $this->getUploadedSheet($rq, $type);
            if (!count($sheet)) $sheet = [[]];
            $errors = $this->getSheetErrors($sheet, $type); 
            if (count($errors)) {
                throw ValidationException::withMessages([
                    'file' => trans("Columns ':cols' not present in file sheet :sheet", [
                        'cols' => implode(', ', $errors),
                        'sheet' => $this->getTypeSheet($type), 
                    ])
                ]);
            }
            
            $rq->session()->put(static::SESSION_FILE_DATA, $sheet);
        }

        return redirect()->route('imports.create.step2', [
                'charge' => $type
                ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function createStep2(Request $request)
    {
        if (null === ($rows = $request->session()->get(static::SESSION_FILE_DATA))) {
            return redirect()->route('imports.create.step1');
        }

        $type = (int) $request->get('charge', InvoiceCharge::TYPE_CASH);

        return view('movements.import.step2', [
            'collection' => $this->getCharges($rows, $type),
            'type'       => $type,
        ]); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function storeStep2(ImportChargeRequest $request)
    {
        $charges = [];
        $errors  = [];
        $values  = $request->all();
        foreach ($values['item'] as $i => $raw) {
            switch ($raw['charge']) {
                case OrderCharge::HZ_PREFIX:
                    $order  = $this->em->getRepository(Order::class)
                                   ->find($raw['order']);

                    $charge = new OrderCharge;
                    $charge->hydrate($raw)
                           ->setOrder($order)
                           ->setSubaccount($order->getSubaccount());
                    break;

                case InvoiceCharge::HZ_PREFIX:
                    $acc = $this->em->getRepository(Subaccount::class)
                                ->find($raw['account']);

                    $charge = new InvoiceCharge;
                    $charge->hydrate($raw)
                           ->setSubaccount($acc);
                    break;
            }    

            try {
                MEv::dispatch($charge, MEv::ACTION_STORE);
            } catch (DuplicatedCharge $e) {
                $errors["item.{$i}.hzentry"][] = $e->getMessage();
            } catch (OrderInvalidStatus $e) {
                $errors["item.{$i}.order"][] = $e->getMessage();
            } catch (AccountInsufficientCredit $e) {
                $errors["item.{$i}.credit"][] = $e->getMessage();
            } catch (InvoicedLimitException $e) {
                $errors["item.{$i}.supplier"][] = $e->getMessage();
            } catch (\Exception $e) {
                throw $e;
            }

            $this->em->persist($charge);
            $charges[] = $charge;
        }

        if ($errors) {
            return back()->withErrors($errors)
                         ->withInput();
        } 

        $this->em->flush();

        return redirect()->route('imports.create.step2')
                         ->with('success', __(':count charges successfully imported', ['count' => count($charges)]));
    }

    /**
     * @param array $row
     * @param int $type
     * @return Charge
     */
    protected function getCharge($row = [], $type)
    {
        $hzPattern  = OrderCharge::HZ_PATTERN;
        $hzSequence = $row['sequence'];
        $hzCode     = "{$row['hzyear']}-{$row['hzentry']}";
        if (preg_match($hzPattern, $hzSequence, $matches)) {
            switch (strtoupper($matches[1])) {
                case InvoiceCharge::HZ_PREFIX:
                    $stored = $this->em
                        ->getRepository(InvoiceCharge::class)
                        ->findOneBy([
                             'hzCode' => $hzCode,
                             'type'   => InvoiceCharge::TYPE_INVOICED,
                         ]);

                    $charge = $stored ? 
                        clone $stored : new InvoiceCharge;
                    $charge->hydrate($row)
                           ->setType($type);
                    $this->hydrateInvoicedAccount($matches[2], $charge);
                    break;
                case OrderCharge::HZ_PREFIX:
                    $stored = $this->em
                        ->getRepository(OrderCharge::class)
                        ->findOneBy([
                             'hzCode' => $hzCode,
                             'type'   => OrderCharge::TYPE_ORDER_INVOICED,
                         ]);

                    $charge = $stored ? 
                        clone $stored : new OrderCharge;
                    $charge->hydrate($row)
                           ->setType(OrderCharge::TYPE_ORDER_INVOICED);
                    $this->hydrateInvoicedOrder($matches[2], $charge);
                    break;
            }
        } 
        else {
            $charge = new Charge;
            $charge->hydrate($row);
            $charge->setType(Charge::TYPE_OTHER);
            $charge->setDetail($hzSequence);
        }

        return $charge;
    }

    /**
     * Retrieve charges
     *
     * @param   array $sheet
     * @param   int $type
     * @return  Charge[]
     */
    protected function getCharges($sheet = [], $type)
    {
        $collection = [];
        $cols       = $this->getTypeColumns($type);
        foreach ($sheet as $row) {
            $parsed = [];
            foreach ($this->getTypeColumns($type) as $key => $col) {
                $parsed[$key] = isset($row[$col]) ? $row[$col] : null;
            }
            $collection[] = $this->getCharge($parsed, $type);
        }
        return $collection;
    }

    /**
     * Retrieve sheet from Uploaded File
     *
     * @param Request $rq
     * @param int $type
     */
    protected function getUploadedSheet(Request $rq, $type)
    {
        return FastExcel::sheet($this->getTypeSheet($type))
                        ->import($rq->file->path());
    }

    /**
     * @param array $sheet
     * @param int $type
     * @throws Exception
     */
    protected function getSheetErrors($sheet = [], $type)
    {
        $errors = [];
        foreach ($this->getTypeColumns($type) as $col) {
            if (!array_key_exists($col, $sheet[0]))
                $errors[] = $col;
        }
        return $errors;
    }   

    /**
     * Get HZ file-type cols
     */
    protected function getTypeColumns($type)
    {
        $keys = [
            'sequence',
            'credit',
            'invoice',
            'invoiceDate',
            'hzyear',
            'hzentry',
        ];
        switch ($type) {
            case InvoiceCharge::TYPE_INVOICED:
                $cols = [
                    self::I_COL_SEQUENCE,
                    self::I_COL_CREDIT,
                    self::I_COL_INVOICE,
                    self::I_COL_INVOICEDATE,
                    self::I_COL_HZYEAR,
                    self::I_COL_HZENTRY,
                ];
                break;
            default:
                $cols = [
                    self::C_COL_SEQUENCE,
                    self::C_COL_CREDIT,
                    self::C_COL_INVOICE,
                    self::C_COL_INVOICEDATE,
                    self::C_COL_HZYEAR,
                    self::C_COL_HZENTRY,
                ];
        }
        return array_combine($keys, $cols);
    }

    /**
     * Get HZ file-type sheet
     */
    protected function getTypeSheet($type)
    {
        switch ($type) {
            case InvoiceCharge::TYPE_INVOICED:
                return self::FILE_INVOICED_SHEET;
            default:
                return self::FILE_CASH_SHEET;
        }
    }
}
