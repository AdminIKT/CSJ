<?php

namespace App\Http\Controllers\InvoiceCharge;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

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

class ImportController extends InvoiceChargeController
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function createStep1(Request $request)
    {
        $request->session()->forget('parsed-inv-charges');

        return view('movements.import.step1', [
            'route' => 'imports.store.step1',
        ]); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);
        if (empty($request->session()->get('parsed-inv-charges'))) {
            $path = $request->file->path();
            $rows = FastExcel::import($path);

            if (count($rows)) {
                $errors = [];
                foreach ([
                    'sequence', 
                    'credit', 
                    'invoice', 
                    'invoiceDate', 
                    'hzyear', 
                    'hzentry'] as $col) {
                    if (!array_key_exists($col, $rows[0]))
                        $errors[] = $col;
                }
                if (count($errors)) {
                throw ValidationException::withMessages(['file' => trans("Columns ':cols' not present", ['cols' => implode(', ', $errors)])]);
                }
            }
            $request->session()->put('parsed-inv-charges', $rows);
        }

        return redirect()->route('imports.create.step2');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function createStep2(Request $request)
    {
        if (null === ($rows = $request->session()->get('parsed-inv-charges'))) {
            return redirect()->route('imports.create.step1');
        }

        return view('movements.import.step2', [
            'collection' => $this->getCharges($rows),
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
                           ->setType(OrderCharge::TYPE_ORDER_INVOICED)
                           ->setOrder($order)
                           ->setSubaccount($order->getSubaccount());
                    break;

                case InvoiceCharge::HZ_PREFIX:
                    $acc = $this->em->getRepository(Subaccount::class)
                                ->find($raw['account']);

                    $charge = new InvoiceCharge;
                    $charge->hydrate($raw)
                           ->setType(OrderCharge::TYPE_INVOICED)
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
     * Retrieve charges
     */
    protected function getCharges($rows = [])
    {
        $collection = [];
        $hzPattern  = OrderCharge::HZ_PATTERN;

        foreach ($rows as $row) {
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
                               ->setType(InvoiceCharge::TYPE_INVOICED);
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
                $collection[] = $charge;
            } 
            else {
                //TODO: 
            }
        }
        return $collection;
    }
}
