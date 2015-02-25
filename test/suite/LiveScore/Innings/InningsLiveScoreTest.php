<?php
// namespace Icecave\Siphon\LiveScore;

// use Eloquent\Phony\Phpunit\Phony;
// use Icecave\Chrono\TimeSpan\Duration;
// use PHPUnit_Framework_TestCase;

// class LiveScoreTest extends PHPUnit_Framework_TestCase
// {
//     public function setUp()
//     {
//         $this->markTestSkipped();

//         $this->gameClock = Phony::fullMock(Duration::class);
//         $this->scope1    = Phony::fullMock(Scope::class);
//         $this->scope2    = Phony::fullMock(Scope::class);

//         $this->liveScore = new LiveScore(
//             $this->gameClock->mock()
//         );
//     }

//     public function testGameClock()
//     {
//         $this->assertSame(
//             $this->gameClock->mock(),
//             $this->liveScore->gameClock()
//         );
//     }

//     public function testGameClockNull()
//     {
//         $this->liveScore = new LiveScore;

//         $this->assertNull(
//             $this->liveScore->gameClock()
//         );
//     }

//     public function testCurrentScope()
//     {
//         $this->liveScore->add(
//             $this->scope1->mock()
//         );

//         $this->liveScore->add(
//             $this->scope2->mock()
//         );

//         $this->assertSame(
//             $this->scope2->mock(),
//             $this->liveScore->currentScope()
//         );
//     }

//     public function testCurrentScopeWhenLastScopeIsComplete()
//     {
//         $this->scope2->status->returns(
//             ScopeStatus::COMPLETE()
//         );

//         $this->liveScore->add(
//             $this->scope1->mock()
//         );

//         $this->liveScore->add(
//             $this->scope2->mock()
//         );

//         $this->assertNull(
//             $this->liveScore->currentScope()
//         );
//     }

//     public function testCurrentScopeWhenEmpty()
//     {
//         $this->assertNull(
//             $this->liveScore->currentScope()
//         );
//     }

//     public function testScopes()
//     {
//         $this->assertSame(
//             [],
//             $this->liveScore->scopes()
//         );

//         $this->liveScore->add(
//             $this->scope1->mock()
//         );

//         $this->liveScore->add(
//             $this->scope2->mock()
//         );

//         $this->assertSame(
//             [
//                 $this->scope1->mock(),
//                 $this->scope2->mock(),
//             ],
//             $this->liveScore->scopes()
//         );
//     }
// }
