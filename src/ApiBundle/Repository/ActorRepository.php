<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ActorRepository extends EntityRepository
{
    public function findOneByName($name)
    {
        return $this->findOneBy(['firstname' => $name]);
    }
}
