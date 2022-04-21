<?php

namespace TripServiceKata\Trip;

use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;
use TripServiceKata\Exception\UserNotLoggedInException;

class TripService
{
    /**
     * @var UserSession
     */
    private $session;

    /**
     * @var TripRepository
     */
    private $tripRepository;

    public function __construct(
        UserSession $session,
        TripRepository $tripRepository
    ) {
        $this->session = $session;
        $this->tripRepository = $tripRepository;
    }

    public function getTripsByUser(User $user)
    {
        if (!$this->hasLoggedUser($loggedUser = $this->getLoggedUserFromSession())) {
            throw new UserNotLoggedInException();
        }

        return $this->isFriend($loggedUser, $user->getFriends())
            ? $this->findTripsByUser($user)
            : [];
    }

    private function getLoggedUserFromSession()
    {
        return $this->session->getLoggedUser();
    }

    private function findTripsByUser(User $user)
    {
        return $this->tripRepository->findTripsByUser($user);
    }

    private function hasLoggedUser(?User $user)
    {
        return $user !== null;
    }

    /**
     * @param User[] $friends
     */
    private function isFriend($loggedUser, array $friends): bool
    {
        return in_array($loggedUser, $friends);
    }
}
