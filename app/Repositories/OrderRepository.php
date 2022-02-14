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

    function lastest($perPage = 5, $pageName= "page") 
    {
        $builder = $this->createQueryBuilder('o');
        $builder->orderBy('o.date' , 'DESC');

        return $this->paginate($builder->getQuery(), $perPage, $pageName);
    }

    /**
     * @param Entity\Area $area
     */
    function fromArea(Entities\Area $area, $perPage = 5, $pageName= "page") 
    {
        $builder = $this->createQueryBuilder('o');
        $builder->where("o.area = {$area->getId()}");
        $builder->orderBy('o.date' , 'DESC');

        return $this->paginate($builder->getQuery(), $perPage, $pageName);
    }

    /**
     * @param Entity\Supplier $supplier
     */
    function fromSupplier(Entities\Supplier $supplier, $perPage = 5, $pageName= "page") 
    {
        $builder = $this->createQueryBuilder('o');
        $builder->innerJoin('o.products', 'p')
                ->innerJoin('p.supplier', 's')
                ->andWhere('s.id = :id')
                ->orderBy('o.created' , 'DESC')
                ->setParameters([
                    'id' => $supplier
                ]);

        return $this->paginate($builder->getQuery(), $perPage, $pageName);
    }
}
