<?php

namespace spec\App\Entity;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Entity\Security;
use App\Exception\DinosaursAreRunningRampantException;
use App\Exception\NotABuffetException;
use PhpSpec\ObjectBehavior;

/**
 * Class EnclosureSpec
 *
 * Enclosure model specification
 *
 * @package spec\App\Entity
 */
class EnclosureSpec extends ObjectBehavior
{
    /**
     * This specifications should be for the enclosure class.
     */
    function it_is_initializable()
    {
        $this->shouldHaveType(Enclosure::class);
    }

    /**
     * Expect enclosures to be empty by defaults
     */
    function it_should_contain_no_dinosaurs_by_default()
    {
        $this->getDinosaurs()->shouldHaveCount(0);
    }

    /**
     * Ensure dinosaurs can be added as expected
     */
    function it_should_be_able_to_add_dinosaurs()
    {
        $this->beConstructedWith(true);
        $this->addDinosaur(new Dinosaur());
        $this->addDinosaur(new Dinosaur());

        $this->getDinosaurs()->shouldHaveCount(2);
    }

    /**
     * Ensure that carnivores and herbivores can not be mixed in enclosures
     */
    function it_should_not_allow_to_add_carnivorous_dinosaurs_to_non_carnivorous_enclosures()
    {
        $this->beConstructedWith(true);
        $this->addDinosaur(new Dinosaur('Herbivore', false));

        $this->shouldThrow(NotABuffetException::class)
            ->during(
                'addDinosaur',
                [new Dinosaur('Carnivore', true)]
            );
    }

    /**
     * Ensure that dinosaurs can not be added to enclosures without security
     */
    function it_should_not_allow_to_add_dinosaurs_to_unsecure_enclosures()
    {
        $this->beConstructedWith(false);

        // Another piece of nonsense .. err magic.
        // duringAddDinosaur spins up method interception for the addDinosaur
        // method, to be honest.. both ways of handling 'durings' are awful
        // but atleast ->during() exists and has a place
        $this->shouldThrow(new DinosaursAreRunningRampantException('Are you crazy!?'))
            ->duringAddDinosaur(new Dinosaur('Velociraptor', true));
    }

    /**
     * Ensure that dinosaurs added to enclosures during instantiation trigger
     * exceptions if that enclosure is not secured.
     */
    function it_should_fail_if_providing_initial_dinosaurs_without_security()
    {
        $this->beConstructedWith(false, new Dinosaur());

        $this->shouldThrow(DinosaursAreRunningRampantException::class)
            ->duringInstantiation();
    }
}
