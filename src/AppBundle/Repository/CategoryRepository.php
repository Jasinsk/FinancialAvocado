<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @param User|null $user
     * @return array
     */
    public function getChoiceOptions($user = null)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c.id, c.name')
            ->from('AppBundle:Category', 'c');
        if (isset($user)) {
            $qb
                ->where('c.userId = :userId')
                ->setParameter('userId', $user->getId());
        }
       return $qb->getQuery()->getArrayResult();
    }

}