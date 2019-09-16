<?php

namespace AppBundle\Repository;

/**
 * RegionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegionRepository extends \Doctrine\ORM\EntityRepository
{
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere("e.name LIKE :q");
        $qb->orderBy('e.name');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->where("MATCH (e.name) AGAINST(:q BOOLEAN) > 0");
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }
}
