<?php

namespace Disjfa\MapsBundle\Security;

use Disjfa\MapsBundle\Entity\MapMarker;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MapMarkerVoter extends Voter
{
    const PATCH = 'patch';
    const POST = 'post';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::PATCH, self::POST))) {
            return false;
        }

        if (!$subject instanceof MapMarker) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param MapMarker $mapMarker
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $mapMarker, TokenInterface $token)
    {
        /** @var UserInterface $user */
        $user = $token->getUser();

        if (false === $user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::POST:
                return $this->canPost($mapMarker, $user);
            case self::PATCH:
                return $this->canPatch($mapMarker, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    /**
     * @param MapMarker $mapMarker
     * @param UserInterface $user
     * @return bool
     */
    private function canPatch(MapMarker $mapMarker, UserInterface $user)
    {
        return $mapMarker->getMap()->getUserId() == $user->getId();
    }

    /**
     * @param MapMarker $mapMarker
     * @param UserInterface $user
     * @return bool
     */
    private function canPost(MapMarker $mapMarker, UserInterface $user)
    {
        return $mapMarker->getMap()->getUserId() == $user->getId();
    }

}
