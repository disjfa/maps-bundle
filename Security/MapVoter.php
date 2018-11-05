<?php

namespace Disjfa\MapsBundle\Security;

use Disjfa\MapsBundle\Entity\Map;
use FOS\UserBundle\Model\UserInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MapVoter extends Voter
{
    const VIEW = 'view';
    const PATCH = 'patch';
    const POST = 'post';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::PATCH, self::POST))) {
            return false;
        }

        if (!$subject instanceof Map) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Map $map
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $map, TokenInterface $token)
    {
        /** @var UserInterface $user */
        $user = $token->getUser();

        if (false === $user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($map, $user);
            case self::POST:
                return $this->canPost($map, $user);
            case self::PATCH:
                return $this->canPatch($map, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    /**
     * @param Map $map
     * @param UserInterface $user
     * @return bool
     */
    private function canView(Map $map, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Map $map
     * @param UserInterface $user
     * @return bool
     */
    private function canPatch(Map $map, UserInterface $user)
    {
        return $map->getUserId() === (string)$user->getId();
    }

    /**
     * @param Map $map
     * @param UserInterface $user
     * @return bool
     */
    private function canPost(Map $map, UserInterface $user)
    {
        return $map->getUserId() === (string)$user->getId();
    }

}
