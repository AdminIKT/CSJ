<?php

namespace App\Repositories;

use App\Entities;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovementRepository extends \Doctrine\ORM\EntityRepository
{
    use \LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

    /**
     * @param Entity\Supplier $supplier
     */
    function fromSupplier(Entities\Supplier $supplier, $perPage = 5, $pageName= "page") 
    {
        $builder = $this->createQueryBuilder('m');
        $builder->innerJoin('m.order', 'o')
                ->andWhere('o.supplier = :id')
                ->orderBy('o.created' , 'DESC')
                ->setParameters([
                    'id' => $supplier
                ]);

        return $this->paginate($builder->getQuery(), $perPage, $pageName);
    }

    /**
     *
     */
    function search(
        $sequence = null, 
        $from = null, 
        $to = null, 
        $account = null, 
        $supplier = null, 
        $otype = null, 
        $mtype = null, 
        $sortBy = "created", 
        $sort = "desc", 
        $perPage = 10, 
        $pageName= "page")
    {
        $builder = $this->createQueryBuilder('m')
                        ->innerJoin('m.order', 'o');

        if ($sequence !== null) {
            $builder->andWhere("o.sequence LIKE :sequence")
                    ->setParameter('sequence', "%{$sequence}%");
        }
        if ($from !== null) {
            $builder->andWhere("m.created >= :from")
                    ->setParameter('from', $from);
        }
        if ($to !== null) {
            $builder->andWhere("m.created <= :to")
                    ->setParameter('to', $to);
        }
        if ($account !== null) {
            $builder->andWhere("o.account = :account")
                    ->setParameter('account', $account);
        }
        if ($supplier !== null) {
            $builder->andWhere("o.supplier = :supplier")
                    ->setParameter('supplier', $supplier);
        }
        if ($otype !== null) {
            $builder->innerJoin('o.account', 'a')
                    ->andWhere("a.type = :otype")
                    ->setParameter('otype', $otype);
        }
        if ($mtype !== null) {
            $builder->andWhere("m.type = :mtype")
                ->setParameter('mtype', $mtype);
        }

        switch ($sortBy) {
            case "sequence":
            case "account":
                $table = "o";
                break;
            default:
                $table = "m";
        }
        $builder->orderBy("{$table}.{$sortBy}" , $sort);

        return $this->paginate($builder->getQuery(), $perPage, $pageName);
    }
}
