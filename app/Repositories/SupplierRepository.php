<?php

namespace App\Repositories;

/**
 * SupplierRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SupplierRepository extends \Doctrine\ORM\EntityRepository
{
    use \LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

    /**
     * @param array{
     *   status: int,
     *   nif: string,
     *   name: string,
     *   city: string,
     *   region: string,
     *   sortBy: string,
     *   sort: string
     *   account: int,
     *   area: int,
     *   orders: bool,
     *   invoices: bool,
     *   estimated: float,
     *   invoiced: float,
     * } $filter
     * @param int $perPage
     * @param string $pageName
     */
    function search(array $filter = [], $perPage = 10, $pageName= "page")
    {
        $b = $this->createQueryBuilder('supplier');

        if ((isset($filter['orders'])
            && $filter['orders'] === true)
            || (isset($filter['account']) 
            && null !== ($account = $filter['account'])) 
            || (isset($filter['area']) 
            && null !== ($area = $filter['area'])) 
            || (isset($filter['invoices']) 
            && $filter['invoices'] === true)
           ) {
            $b->innerJoin('supplier.orders', 'orders');
            $b->innerJoin('orders.subaccount', 'subaccount');
        }

        if (isset($filter['account']) &&
            null !== ($account = $filter['account'])) {
            $b->andWhere("subaccount.account = :account")
              ->setParameter('account', $account);
        }

        if (isset($filter['area']) &&
            null !== ($area = $filter['area'])) {
            $b->andWhere("subaccount.area = :area")
              ->setParameter('area', $area);
        }

        if (isset($filter['invoices']) &&
            $filter['invoices'] === true) {
            $b->innerJoin('orders.orderCharges', 'movements');
        }

        if ((isset($filter['estimated'])
            && null !== ($estimated = $filter['estimated'])) 
            || (isset($filter['credit']) 
            && null !== ($credit = $filter['credit'])) 
        ) {
            $b->leftJoin('supplier.invoiced', 'invoiced')
              ->andWhere('invoiced.year = :year')
              ->setParameter('year', date('Y'));
        }

        if (isset($filter['estimated']) &&
            null !== ($estimated = $filter['estimated'])) {
            $op = $filter['estimatedOp'];
            $b->andWhere("invoiced.estimated {$op} :estimated")
              ->setParameter('estimated', $estimated);
        }
        if (isset($filter['credit']) &&
            null !== ($credit = $filter['credit'])) {
            $op = $filter['creditOp'];
            $b->andWhere("invoiced.credit {$op} :credit")
              ->setParameter('credit', $credit);
        }

        if (isset($filter['status']) &&
            null !== ($status = $filter['status'])) {
            $b->andWhere("supplier.status = :status")
              ->setParameter('status', $status);
        }

        if (isset($filter['nif']) &&
            null !== ($nif = $filter['nif'])) {
            $b->andWhere("supplier.nif LIKE :nif")
              ->setParameter('nif', "%{$nif}%");
        }
        
        if (isset($filter['name']) &&
            null !== ($name = $filter['name'])) {
            $b->andWhere("supplier.name LIKE :name")
              ->setParameter('name', "%{$name}%");
        }

        if (isset($filter['city']) &&
            null !== ($city = $filter['city'])) {
            $b->andWhere("supplier.city LIKE :city")
              ->setParameter('city', "%{$city}%");
        }

        if (isset($filter['region']) &&
            null !== ($region = $filter['region'])) {
            $b->andWhere("supplier.region LIKE :region")
              ->setParameter('region', "%{$region}%");
        }

        $b->orderBy(
            array_key_exists('sortBy', $filter) ?
                    $filter['sortBy'] : 'supplier.name',
            array_key_exists('sort', $filter) ?
                    $filter['sort'] : 'ASC'
        );

        if (!$perPage) {
            $perPage = clone $b;
            $perPage = $perPage->select('count(supplier.id)')
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
     * @return array
     */
    function cities()
    {
        $query = $this->getEntityManager()
                      ->createQuery("
        SELECT DISTINCT(s.city) as cities FROM App\Entities\Supplier s
        WHERE LENGTH(s.city) > 1
        GROUP BY cities ORDER BY cities ASC");

        return $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SCALAR_COLUMN);
    }

    /**
     * @return array
     */
    function regions()
    {
        $query = $this->getEntityManager()
                      ->createQuery("
        SELECT DISTINCT(s.region) as regions FROM App\Entities\Supplier s
        WHERE LENGTH(s.region) > 1
        GROUP BY regions ORDER BY regions ASC");

        return $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_SCALAR_COLUMN);
    }
}
