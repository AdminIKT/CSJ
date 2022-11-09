<?php

namespace App\Repositories;

/**
 * AccountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountRepository extends \Doctrine\ORM\EntityRepository
{
    use \LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

    /**
     * @param array{
     *   name: string,
     *   type: string,
     *   area: int,
     *   user: int,
     *   compromisedOp: float. Required with compromised,
     *   compromised: float,
     *   availableOp: float. Required with available,
     *   available: float,
     *   creditOp: float. Required with credit,
     *   credit: float,
     *   sortBy: string,
     *   sort: string
     * } $search
     * @param int $perPage
     * @param string $pageName
     */
    function search(array $filter = [], $perPage = 10, $pageName= "page")
    {
        $b = $this->createQueryBuilder('account')
                  ->innerJoin('account.subaccounts', 'subaccount');

        if (isset($filter['name']) &&
            null !== ($name = $filter['name'])) {
            $b->andWhere("account.name LIKE :name")
              ->setParameter('name', "%{$name}%");
        }
        if (isset($filter['type']) &&
            null !== ($type = $filter['type'])) {
            $b->andWhere("account.type = :type")
              ->setParameter('type', $type);
        }
        if (isset($filter['area']) &&
            null !== ($area = $filter['area'])) {
            $b->andWhere("subaccount.area = :area")
              ->setParameter('area', $area);
        }
        if (isset($filter['user']) &&
            null !== ($user = $filter['user'])) {
            $b->innerJoin('account.users', 'users')
              ->andWhere("users.id IN (:user)")
              ->setParameter('user', [$user]);
        }
        if (isset($filter['compromised']) &&
            null !== ($compromised = $filter['compromised'])) {
            $op = $filter['compromisedOp'];
            $b->andWhere("account.compromisedCredit {$op} :compromised")
              ->setParameter('compromised', $compromised);
        }
        if (isset($filter['available']) &&
            null !== ($available = $filter['available'])) {
            $op = $filter['availableOp'];
            $b->andWhere("(account.credit - account.compromisedCredit) {$op} :available")
              ->setParameter('available', $available);
        }
        if (isset($filter['credit']) &&
            null !== ($credit = $filter['credit'])) {
            $op = $filter['creditOp'];
            $b->andWhere("account.credit {$op} :credit")
              ->setParameter('credit', $credit);
        }
        $b->orderBy(
            array_key_exists('sortBy', $filter) ?
                    $filter['sortBy'] : 'account.name',
            array_key_exists('sort', $filter) ?
                    $filter['sort'] : 'DESC'
        );

        if (!$perPage) {
            $perPage = clone $b;
            $perPage = $perPage->select('count(account.id)')
                               ->getQuery()
                               ->getSingleScalarResult();
        }

        return $this->paginate(
            $b->getQuery(), 
            $perPage ?: Config('app.per_page'), 
            $pageName);
    }
}
