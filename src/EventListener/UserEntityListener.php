<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserEntityListener
{
    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        if ($user->getCreatedAt() === null) {
            $user->setCreatedAt(new \DateTime());
        }
    }
}
