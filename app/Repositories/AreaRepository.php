<?php

namespace App\Repositories;

/**
 * AreaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AreaRepository extends \Doctrine\ORM\EntityRepository
{
    use \LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

    /**
     *
     */
    function search(
        $name          = null,
        $type          = null,
        $creditOp      = null,
        $credit        = null,
        $compromisedOp = null,
        $compromised   = null,
        $sortBy        = "name", 
        $sort          = "desc", 
        $perPage       = 10, 
        $pageName      = "page")
    {
        $builder = $this->createQueryBuilder('a')
                        ->innerJoin('a.department', 'd');

        if ($name !== null) {
            $builder->andWhere("d.name LIKE :name")
                    ->setParameter('name', "%{$name}%");
        }
        if ($type !== null) {
            $builder->andWhere("a.type = :type")
                    ->setParameter('type', $type);
        }
        if ($credit !== null) {
            $builder->andWhere("a.credit {$creditOp} :credit")
                    ->setParameter('credit', $credit);
        }
        if ($compromised !== null) {
            $builder->andWhere("a.compromisedCredit {$compromisedOp} :compromised")
                    ->setParameter('compromised', $compromised);
        }
        $builder->orderBy("d.{$sortBy}" , $sort);
        return $this->paginate($builder->getQuery(), $perPage, $pageName);
    }
}
