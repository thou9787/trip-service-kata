<?php

namespace Test\TripServiceKata\Trip;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\Trip\Trip;
use TripServiceKata\Trip\TripRepository;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;

class TripServiceTest extends TestCase
{
    /**
     * @var User&MockObject
     */
    private $kent;

    /**
     * @var TripService
     */
    private $target;

    /**
     * @var UserSession&MockObject
     */
    private $mockedUserSession;

    /**
     * @var TripRepository&MockObject
     */
    private $mockedTripRepository;

    protected function setUp(): void
    {
        $this->kent = new User('Kent');
        $this->mockedUserSession = $this->createMock(UserSession::class);
        $this->mockedTripRepository = $this->createMock(TripRepository::class);
        $this->target = new TripService(
            $this->mockedUserSession,
            $this->mockedTripRepository
        );
    }

    /**
     * @test
     */
    public function loginFailed()
    {
        $this->expectException(UserNotLoggedInException::class);

        $this->mockedUserSession->method('getLoggedUser')
            ->willReturn(null);
        $this->target->getTripsByUser($this->kent);
    }

    /**
     * @test
     */
    public function loggedUserIsNotFriend()
    {
        $this->mockedUserSession->method('getLoggedUser')
            ->willReturn(new User('friendB'));
        $this->kent->addFriend(new User('friendA'));
        $result = $this->target->getTripsByUser($this->kent);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function loggedUserIsFriend()
    {
        $this->mockedUserSession->method('getLoggedUser')
            ->willReturn(new User('friendB'));
        $this->kent->addFriend(new User('friendB'));
        $this->kent->addTrip(new Trip());
        $this->mockedTripRepository->method('findTripsByUser')
            ->with($this->kent)
            ->willReturn($this->kent->getTrips());
        $result = $this->target->getTripsByUser($this->kent);
        $this->assertNotEmpty($result);
    }
}
