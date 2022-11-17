<?php

namespace App\Exceptions\Account;

use App\Entities\Subaccount;

class InsufficientCreditException extends \Exception
{
    /**
     * @inheritDoc
     */
    public function __construct(Subaccount $acc, float $credit, $code = 0, \Throwable $prev = null)
    {
        $msg = __("Credit of :credit€ exceeds the available credit of the :account account set to :available€", [
            'credit'    => number_format($credit, 2, ",", "."), 
            'account'   => $acc->getSerial(),
            'available' => number_format($acc->getAvailableCredit(), 2, ",", "."),
            ]);

        parent::__construct($msg, $code, $prev);
    }
}
