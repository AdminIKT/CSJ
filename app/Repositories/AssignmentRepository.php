<?php

namespace App\Repositories;

use App\Entities;

/**
 * AssignmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AssignmentRepository extends \Doctrine\ORM\EntityRepository
{
    use \LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

    /**
     *
     */
    function search(
        $year     = null,
        $account     = null,
        $type     = null,
        $op       = null,
        $credit   = null,
        $sortBy   = "created", 
        $sort     = "desc", 
        $perPage  = 10, 
        $pageName = "page")
    {
        $builder = $this->createQueryBuilder('a');

        //if ($year !== null) {
        //    $builder->andWhere("YEAR(a.created) = :year")
        //            ->setParameter('year', $year);
        //}
        //if ($account !== null) {
        //    $builder->andWhere("a.account = :account")
        //            ->setParameter('account', $account);
        //}
        //if ($type !== null) {
        //    $builder->andWhere("a.type = :type")
        //            ->setParameter('type', $type);
        //}
        //if ($credit !== null) {
        //    $builder->andWhere("a.credit {$op} :credit")
        //            ->setParameter('credit', $credit);
        //}

        //$builder->orderBy("a.{$sortBy}" , $sort);

        //dd($builder->getQuery()->getSql(), $builder->getQuery()->getParameters());
        return $this->paginate($builder->getQuery(), $perPage, $pageName);
    }

    /**
     * @return array
     */
    function years(Entities\Account $account = null)
    {
        $sql  = "SELECT DISTINCT(YEAR(a.created)) as years FROM App\Entities\Assignment a ";
        $sql .= "INNER JOIN App\Entities\Subaccount s ";
        if ($account) 
        $sql .= "WHERE s.account = :account ";
        $sql .= "GROUP BY years ORDER BY years DESC";

        $query = $this->getEntityManager()
                      ->createQuery($sql);
        if ($account)
        $query->setParameter('account', $account->getId());

        return $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SCALAR_COLUMN);
    }
}
