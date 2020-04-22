<?php

namespace spec\App\Factory;

use App\Entity\Dinosaur;
use App\Factory\DinosaurFactory;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;

/**
 * Class DinosaurFactorySpec
 *
 * Specification for the DinosaurFactory
 *
 * @package spec\App\Factory
 */
class DinosaurFactorySpec extends ObjectBehavior
{
    /**
     * Ensure specifying the correct object/class
     */
    function it_is_initializable()
    {
        $this->shouldHaveType(DinosaurFactory::class);
    }

    /**
     * DinosaurFactory should be able to grow a large velociraptor
     */
    function it_can_grow_a_large_velocirpator()
    {
        $dinosaur = $this->growVelociraptor(5);

        $dinosaur->shouldBeAnInstanceOf(Dinosaur::class);
        $dinosaur->getGenus()->shouldBeString();
        $dinosaur->getGenus()->shouldReturn('Velociraptor');
        $dinosaur->getLength()->shouldReturn(5);
    }

    /**
     * Dinosaur factories should be able to grow small directories using the
     * third party nanny dependency. Made up dependency.. dont go looking for
     * it.
     *
     * Example below highlights the use of the skipping exception.
     *
     * @throws \PhpSpec\Exception\Example\SkippingException
     */
    function it_grows_a_small_velociraptor()
    {
        if (!class_exists('Nanny')) {
            throw new SkippingException('Someone needs to look over dino puppies');
        }

        $this->growVelociraptor(1)->shouldBeAnInstanceOf(Dinosaur::class);
    }

    /**
     * Dinosaur factories should be able to grow triceratops.
     *
     * Leaving an example method empty will flag it as pending when running
     * phpspec. This is useful when acknowledging future features that are
     * not yet planned for development.
     */
    function it_grows_a_triceratops()
    {
    }
}
