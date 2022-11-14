<?php

namespace App\Repositories;

use App\Entities;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends \Doctrine\ORM\EntityRepository
{
    use \LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

    /**
     * @param array{
     *   sequence: string,
     *   type: string,
     *   from: string. Date format,
     *   to: string. Date format,
     *   area: int,
     *   account: int,
     *   supplier: int,
     *   user: int,
     *   receiveIn: int,
     *   estimatedOp: float. Required with estimated,
     *   estimated: float,
     *   creditOp: float. Required with credit,
     *   credit: float,
     *   status: int,
     *   sortBy: string,
     *   sort: string
     * } $search
     * @param int $perPage
     * @param string $pageName
     */
    function search(array $filter = [], $perPage = 10, $pageName= "page")
    {
        $b = $this->createQueryBuilder('orders')
                  ->innerJoin('orders.subaccount', 'subaccount')
                  ->innerJoin('subaccount.account', 'account');

        if (isset($filter['sequence']) &&
            null !== ($sequence = $filter['sequence'])) {
            $b->andWhere("orders.sequence LIKE :sequence")
              ->setParameter('sequence', "%{$sequence}%");
        }
        if (isset($filter['from']) &&
            null !== ($from = $filter['from'])) {
            $b->andWhere("orders.date >= :from")
              ->setParameter('from', $from);
        }
        if (isset($filter['to']) &&
            null !== ($to = $filter['to'])) {
            $b->andWhere("orders.date <= :to")
              ->setParameter('to', $to);
        }
        if (isset($filter['type']) &&
            null !== ($type = $filter['type'])) {
            $b->andWhere("account.type = :type")
              ->setParameter('type', $type);
        }
        if (isset($filter['receiveIn']) &&
            null !== ($receiveIn = $filter['receiveIn'])) {
            $b->andWhere("orders.receiveIn = :receiveIn")
              ->setParameter('receiveIn', $receiveIn);
        }
        if (isset($filter['status']) &&
            null !== ($status = $filter['status'])) {
            $b->andWhere("orders.status = :status")
              ->setParameter('status', $status);
        }
        if (isset($filter['estimated']) &&
            null !== ($estimated = $filter['estimated'])) {
            $op = $filter['estimatedOp'];
            $b->andWhere("orders.estimatedCredit {$op} :estimated")
              ->setParameter('estimated', $estimated);
        }
        if (isset($filter['credit']) &&
            null !== ($credit = $filter['credit'])) {
            $op = $filter['creditOp'];
            $b->andWhere("orders.credit {$op} :credit")
              ->setParameter('credit', $credit);
        }
        if (isset($filter['area']) &&
            null !== ($area = $filter['area'])) {
            $b->andWhere("subaccount.area = :area")
              ->setParameter('area', $area);
        }
        if (isset($filter['account']) &&
            null !== ($account = $filter['account'])) {
            $b->andWhere("subaccount.account = :account")
              ->setParameter('account', $account);
        }
        if (isset($filter['supplier']) &&
            null !== ($supplier = $filter['supplier'])) {
            $b->andWhere("orders.supplier = :supplier")
              ->setParameter('supplier', $supplier);
        }
        if (isset($filter['user']) &&
            null !== ($user = $filter['user'])) {
            $b->andWhere("orders.user = :user")
              ->setParameter('user', $user);
        }

        $b->orderBy(
            array_key_exists('sortBy', $filter) ?
                    $filter['sortBy'] : 'orders.date',
            array_key_exists('sort', $filter) ?
                    $filter['sort'] : 'DESC'
        );

        if (!$perPage) {
            $perPage = clone $b;
            $perPage = $perPage->select('count(orders.id)')
                               ->getQuery()
                               ->getSingleScalarResult();
        }

        //dd($b->getQuery()->getSql(), $b->getQuery()->getParameters());

        return $this->paginate(
            $b->getQuery(), 
            $perPage ?: Config('app.per_page'), 
            $pageName);
    }

    /**
     * @param Account $account
     * @return Order|null
     */
    function lastest(Entities\Account $account) 
    {
        return $this->createQueryBuilder('o')
                    ->innerJoin('o.subaccount', 's')
                    ->andWhere('s.account = :account')
                    ->setParameter('account', $account->getId())
                    ->orderBy('o.date', 'desc')
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();
    
    }
}
