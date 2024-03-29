<?php

namespace App\Repositories;

use App\Entities;

/**
 * OrderChargeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderChargeRepository extends InvoiceChargeRepository
{
    /**
     * @inheritDoc
     */
    protected function getQueryBuilder(array $filter = [])
    {
        $builder = parent::getQueryBuilder($filter);

        if (isset($filter['order']) &&
            null !== ($order = $filter['order'])) {
            $builder->andWhere("movement.order = :order")
                    ->setParameter('order', $order);
        }
        if (isset($filter['supplier']) &&
            null !== ($supplier = $filter['supplier'])) {
            $builder->innerJoin('movement.order', 'orders')
                    ->andWhere("orders.supplier = :supplier")
                    ->setParameter('supplier', $supplier);
        }

        return $builder;
    }
}
