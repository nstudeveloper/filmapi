<?php

namespace ApiBundle\Repository;

class ActorRepository extends \Doctrine\ORM\EntityRepository
{
    public function findOneByName($name)
    {
        return $this->findOneBy(['firstName' => $name]);
    }
}
