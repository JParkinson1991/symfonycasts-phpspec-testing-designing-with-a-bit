<?php

namespace spec\App\Service;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Factory\DinosaurFactory;
use App\Service\EnclosureBuilderService;
use App\Service\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class EnclosureBuilderServiceSpec
 *
 * @package spec\App\Service
 */
class EnclosureBuilderServiceSpec extends ObjectBehavior
{
    /**
     * Example case boostrapper.
     *
     * This method is called prior to all the it_ its_ example methods.
     *
     * This method will work exactly like the example methods, ie define
     * parameters to receive test doubles.
     *
     * MORE MAGIC:
     * How do we pass this exact double from this method to our example
     * methods? Simple the paramter variable name. Keep the name of the
     * variables the same, and that same object will be used where it is
     * type hinted.
     *
     * Not certain the above works between examples, but it definitely works
     * from let() -> it_*() and let() its_*()
     *
     * Why does this matter? Again simple, the object we're testing is being
     * constructed with this doubles. If we construct an object with one
     * double, but then create the assertations against a different double in
     * the example method.. obviously the test will fail, why? Because the
     * object that was injected into our class WAS having the methods called
     * against, but we were asserting that they were called on object that had
     * not been injected into the class.. thus the methods were not actually
     * called. Try it.. change $entityManager to something else and watch
     * the example it_builds_enclosure_with_dinosaurs() fail.
     */
    function let(DinosaurFactory $dinosaurFactory, EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($dinosaurFactory, $entityManager);
    }

    /**
     * Writing specification for the enclosure builder service
     */
    function it_is_initializable()
    {
        $this->shouldHaveType(EnclosureBuilderService::class);
    }

    /**
     * Ensure the enclosure builder server can do just that, build enclosures.
     *
     * Prophecy/Mocking Vocabulary
     *
     * - DUMMY | double object but define no behaviours (method returns) or
     *           make any asserts on how it is called
     * - STUBS | double object that has its methods return values defined
     * - MOCKS | double objects with assertions on how many times methods are
     *           called etc, but these assertions are made before the code
     *           test/case is actually run
     * - SPIES | Exact same as a mock, but you add the method call count
     *           assertations after the code test/case has run.
     *
     * MOCKS Vs SPIES
     * - Mock: You should be called on x times.
     * - Spy : Were you called x times?
     *
     * You can be a STUB and a MOCK/SPY at the same time.
     *
     * @param \App\Factory\DinosaurFactory|\PhpSpec\Wrapper\Collaborator $dinosaurFactory
     */
    function it_builds_enclosure_with_dinosaurs(DinosaurFactory $dinosaurFactory, EntityManagerInterface $entityManager)
    {
        // Define dinosaur to return via mock
        $dino1 = new Dinosaur('Stegosaurus', false);
        $dino1->setLength(6);

        $dino2 = new Dinosaur('Baby Stegosaurus', false);
        $dino2->setLength(2);

        // Arguments passed to mock object methods will only override the
        // return for that method when it is called with those arguments
        //
        // If we call growVelociraptor with a different length it will
        // return null as we haven't mocked the method in that context.
        //
        // Passing multiple return values to the willReturn method will
        // result in the method returning different values for each call
        //
        // If the method is called more times than return values specified
        // the last return value defined will be reused. In this example
        // if growVelociraptor is called 4 times with length of 5 it will
        // return: $dino1, $dino2, $dino2, $dino2
        //
        //$dinosaurFactory->growVelociraptor(5)->willReturn(
        //    $dino1,
        //    $dino2
        //);
        //
        // HOWEVER.. hardcoding expected method parameters can cause us alot
        // of unnecessary headaches.. especially when dynamic values are used
        // in the services implementation of the mocked method calls. Dont
        // hardcode.. use dynamic argument matchers. Argument::any() for
        // example
        $dinosaurFactory->growVelociraptor(Argument::type('int'))->willReturn(
            $dino1,
        );

        // Order of method promises is not important, propechy.. the underlying
        // mocker for phpspec will gather all method promises and use them in
        // order of how specific each one is.
        //
        // For example, this method pormise and the Argument::type('int')
        // promise will both match a method call to growVelociraptor with
        // value 5. However, as 5 === 5 is more specific than 5 === integer
        // the most specific promise is used (this one) and $dino2 is
        // returned.
        $dinosaurFactory->growVelociraptor(5)->willReturn(
            $dino2
        );

        // IMPORTANT.. the above 'stubs'.. mocks.. whatever only dictate
        // what should be returned ---> IF <--- !! IF the method is called.
        // There is no assertation that the method has to be called. Assert
        // that the method is called x number of times..
        //
        // Number of times method were called checks can be handled before or
        // after the tests process see bottom of method for example
        $dinosaurFactory->growVelociraptor(Argument::type('int'))
            ->shouldBeCalledTimes(2);
            //->willReturn() <--- These methods can be chained

        $enclosure = $this->buildEnclosure(1, 2);

        $enclosure->shouldBeAnInstanceOf(Enclosure::class);
        $enclosure->isSecurityActive()->shouldReturn(true);

        $enclosure->getDinosaurs()->shouldHaveCount(2);
        $enclosure->getDinosaurs()[0]->shouldBe($dino2);
        $enclosure->getDinosaurs()[1]->shouldBe($dino1);

        // After running test process, ensure the mocked dinosaur factory
        // had specific methods called an expected number of times
        $dinosaurFactory->growVelociraptor(Argument::type('int'))
            ->shouldHaveBeenCalledTimes(2);

        // Here we check that entity manager was persisted
        // THIS IS A SPY
        $entityManager->persist(Argument::type(Enclosure::class))
            ->shouldHaveBeenCalledOnce();
        $entityManager->flush()
            ->shouldHaveBeenCalledOnce();
    }
}
