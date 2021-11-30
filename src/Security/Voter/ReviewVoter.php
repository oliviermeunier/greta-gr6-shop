<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewVoter extends Voter
{
    const DELETE = 'DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['DELETE'])
            && $subject instanceof \App\Entity\Review;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Si  l'utilisateur a le rôle ADMIN...
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true; // ... alors il a le droit de faire l'action ! 
        }

        $review = $subject;

        switch ($attribute) {
            // L'utilisateur s'il n'est pas ADMIN peut supprimer un avis...
            case self::DELETE: 

                // ... s'il est l'auteur de l'avis
                return $user == $review->getUser();
        }

        return false;
    }
}
