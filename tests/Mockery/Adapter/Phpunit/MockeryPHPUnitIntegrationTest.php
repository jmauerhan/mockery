<?php

namespace test\Mockery\Adapter\Phpunit;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Exception\BadMethodCallException;

class BaseClassStub 
{
    use MockeryPHPUnitIntegration;

    function finish() {
        $this->checkMockeryExceptions();
    } 
};

class MockeryPHPUnitIntegrationTest extends MockeryTestCase
{
    /** @test */
    public function it_marks_a_passing_test_as_risky_if_we_threw_exceptions()
    {
        $mock = mock();
        try {
            $mock->foobar();
        } catch (\Exception $e) {
            // exception swallowed...
        }

        $test = spy(BaseClassStub::class)->makePartial();
        $test->finish();

        $test->shouldHaveReceived()->markAsRisky(m::type("string"));
    }

    /** @test */
    public function the_user_can_manually_dismiss_an_exception_to_avoid_the_risky_test()
    {
        $mock = mock();
        try {
            $mock->foobar();
        } catch (BadMethodCallException $e) {
            $e->dismiss();
        }

        $test = spy(BaseClassStub::class)->makePartial();
        $test->finish();

        $test->shouldNotHaveReceived()->markAsRisky(m::any());
    }
}