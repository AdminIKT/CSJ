<?php

namespace App\Repositories;

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
     * @param array $filter
     * @return QueryBuilder
     */
    protected function getQueryBuilder(array $filter = [])
    {
        $builder = $this->createQueryBuilder('movement')
                        ->innerJoin('movement.subaccount', 'subaccount')
                        ->innerJoin('subaccount.area', 'area')
                        ->innerJoin('subaccount.account', 'account');

        if (isset($filter['type']) &&
            null !== ($type = $filter['type'])) {
            $builder->andWhere("movement.type = :type")
                    ->setParameter('type', $type);
        }
        if (isset($filter['from']) &&
            null !== ($from = $filter['from'])) {
            $builder->andWhere("movement.created >= :from")
                    ->setParameter('from', $from);
        }
        if (isset($filter['to']) &&
            null !== ($to = $filter['to'])) {
            $builder->andWhere("movement.created <= :to")
                    ->setParameter('to', $to);
        }
        if (isset($filter['account']) &&
            null !== ($account = $filter['account'])) {
            $builder->andWhere("subaccount.account = :account")
                    ->setParameter('account', $account);
        }
        if (isset($filter['area']) &&
            null !== ($area = $filter['area'])) {
            $builder->andWhere("subaccount.area = :area")
                    ->setParameter('area', $area);
        }
        if (isset($filter['credit']) &&
            null !== ($credit = $filter['credit'])) {
            $op = $filter['operator'];
            $builder->andWhere("movement.credit {$op} :credit")
                    ->setParameter('credit', $credit);
        }
        if (isset($filter['detail']) &&
            null !== ($detail = $filter['detail'])) {
            $builder->andWhere("movement.detail LIKE :detail")
                    ->setParameter('detail', "%{$detail}%");
        }

        $builder->orderBy(
            array_key_exists('sortBy', $filter) ?
                    $filter['sortBy'] : 'movement.created',
            array_key_exists('sort', $filter) ?
                    $filter['sort'] : 'DESC'
        );
    
        return $builder;
    }

    /**
     * @param array{
     *   type: int,
     *   from: string,
     *   to: string. Date format,
     *   area: int,
     *   account: int,
     *   operator: float. Required with credit,
     *   credit: float,
     * } $search
     */
    function search(array $filter = [], $perPage = 10, $pageName= "page")
    {

        $builder = $this->getQueryBuilder($filter);

        if (!$perPage) {
            $perPage = clone $builder;
            $perPage = $perPage->select('count(movement.id)')
                               ->getQuery()
                               ->getSingleScalarResult();
        }

        //dd($b->getQuery()->getSql(), $b->getQuery()->getParameters());

        return $this->paginate(
            $builder->getQuery(), 
            $perPage ?: Config('app.per_page'), 
            $pageName);
    }

    /**
     * @return array
     */
    function years(App\Entities\Account $account = null)
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
