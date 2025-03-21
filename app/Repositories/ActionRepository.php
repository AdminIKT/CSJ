<?php

namespace App\Repositories;

/**
 * ActionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActionRepository extends \Doctrine\ORM\EntityRepository
{
    use \LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

    /**
     * @param array $filter
     * @return QueryBuilder
     */
    protected function getQueryBuilder(array $filter = [])
    {
        $builder = $this->createQueryBuilder('action');

        if (isset($filter['type']) &&
            null !== ($type = $filter['type'])) {
            $builder->andWhere("action.type = :type")
                    ->setParameter('type', $type);
        }
        if (isset($filter['from']) &&
            null !== ($from = $filter['from'])) {
            $builder->andWhere("action.created >= :from")
                    ->setParameter('from', $from);
        }
        if (isset($filter['to']) &&
            null !== ($to = $filter['to'])) {
            $builder->andWhere("action.created <= :to")
                    ->setParameter('to', $to);
        }
        if (isset($filter['user']) &&
            null !== ($user = $filter['user'])) {
            $builder->andWhere("action.user = :user")
                    ->setParameter('user', $user);
        }
        if (isset($filter['action']) &&
            null !== ($action = $filter['action'])) {
            $builder->andWhere("action.action = :action")
                    ->setParameter('action', $action);
        }

        $builder->orderBy(
            array_key_exists('sortBy', $filter) ?
                    $filter['sortBy'] : 'action.created',
            array_key_exists('sort', $filter) ?
                    $filter['sort'] : 'DESC'
        );
    
        return $builder;
    }

    /**
     * @param array{
     * } $filter
     * @param int $perPage
     * @param string $pageName
     */
    function search(array $filter = [], $perPage = 10, $pageName= "page")
    {
        $b = $this->getQueryBuilder($filter);

        $b->orderBy(
            array_key_exists('sortBy', $filter) ?
                    $filter['sortBy'] : 'action.created',
            array_key_exists('sort', $filter) ?
                    $filter['sort'] : 'DESC'
        );

        if (!$perPage) {
            $perPage = clone $b;
            $perPage = $perPage->select('count(action.id)')
                               ->getQuery()
                               ->getSingleScalarResult();
        }

        return $this->paginate(
            $b->getQuery(), 
            $perPage ?: Config('app.per_page'), 
            $pageName);
    }
}
