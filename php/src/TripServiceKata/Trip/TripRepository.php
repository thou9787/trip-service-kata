<?php

namespace TripServiceKata\Trip;

use TripServiceKata\Trip\TripDAO;
use TripServiceKata\User\User;

class TripRepository
{
    public function findTripsByUser(User $user)
    {
        return TripDAO::findTripsByUser($user);
    }
}