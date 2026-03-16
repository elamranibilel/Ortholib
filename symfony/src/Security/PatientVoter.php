<?php

namespace App\Security;

use App\Entity\Patient;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PatientVoter extends Voter
{
    public const VIEW = 'view_patient';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::VIEW && $subject instanceof Patient;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface || !$subject instanceof Patient) {
            return false;
        }

        if (!in_array('ROLE_PATIENT', $user->getRoles(), true)) {
            return false;
        }

        // Comparer les ID au lieu des objets
        if ($user instanceof Patient) {
            return $user->getId() === $subject->getId();
        }

        return false;
    }
}
