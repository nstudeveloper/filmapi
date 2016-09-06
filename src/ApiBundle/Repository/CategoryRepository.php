<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}
