<?php

namespace Example4Bundle\Repository;

use Doctrine\ORM\EntityRepository;
use Example4Bundle\Entity\User;

class UserRepository extends EntityRepository
{
    public function findByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param User $user
     */
    public function add(User $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}