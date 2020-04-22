<?php

namespace spec\App\Entity;

use App\Entity\Dinosaur;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

/**
 * Class DinosaurSpec
 *
 * Defines the specification for the Dinosaur entity.
 *
 * Notes of spec classes coding standards violations:
 *     Method scope (public, protected, private) is omitted to improve
 *     readability. PHP by default will apply the public scope to methods
 *     that do not explicitly define one, thus they are still accessible by the
 *     phpspec library.
 *
 *     Method names defined in snake case for readability again. phpspec scans
 *     method names for the prefixes 'it_' and 'its_' and runs them as part
 *     of execution, simply follow suite and snake case the rest of the method.
 *     Also, it seems method names are 'prettied' for cli output, underscores
 *     removed during cli render so that method names look like human readable
 *     sentences.
 *
 * @package spec\App\Entity
 */
class DinosaurSpec extends ObjectBehavior
{
    /**
     * Sets matchers for use with this specification.
     *
     * All matches defined can be prefixed with should and shouldNot throughout
     * specification classes.
     *
     * @return array
     *     Key => The suffix of the matcher method call in camel case, ie,
     *            matcher method = shouldReturnZero
     *            key = returnZero
     *     Value => The callback function used to determine a match returning
     *              either true or false.
     *              To override error messages that are output, throw failure
     *              exceptions when $subject does not match the expected.
     */
    public function getMatchers(): array
    {
        return array_merge(parent::getMatchers(), [
            'returnZero' => function($subject) {
                if ($subject !== 0) {
                    throw new FailureException(sprintf(
                        'Returned value should be zero, got "%s"',
                        $subject
                    ));
                }

                return true;
            }
        ]);
    }

    /**
     * Specification/Description/Example method
     *
     * phpspec wil ready all methods in this class that a prefixed with
     * it_ or its_
     */
    function it_is_initializable()
    {
        // Treat $this like the class being specified
        // ie. $this === object instance of App\Entity\Dinosaur
        // Interfaces will be the same
        $this->shouldHaveType(Dinosaur::class);
    }

    /**
     * Dinosaurs should have a default length of 0
     */
    function it_should_default_to_zero_length()
    {
        $this->getLength()->shouldReturn(0);
    }

    /**
     * Checks that dinosaurs have a default length of 0 using a custom matcher
     */
    function it_should_default_to_zero_length_using_custom_matcher()
    {
        $this->getLength()->shouldReturnZero();
    }

    /**
     * Dinosaurs should be able to have their length set
     */
    function it_should_allow_to_set_length()
    {
        $this->setLength(9);
        $this->getLength()->shouldReturn(9);
    }

    /**
     * Dinosaurs should not shrink more than expected
     */
    function it_should_not_shrink()
    {
        $this->setLength(15);

        $this->getLength()->shouldBeGreaterThan(12);
    }

    /**
     * Dinosaurs should be able to return a full description of themselves
     */
    function it_should_return_full_description()
    {
        $this->getDescription()->shouldReturn('The Unknown non-carnivorous dinosaur is 0 meters long');
    }

    /**
     * Dinosaurs description should depend on construction parameters
     */
    function it_should_return_full_description_for_tyrannosaurus()
    {
        $this->beConstructedWith('Tyrannosaurus', true);
        $this->setLength(12);

        $this->getDescription()->shouldReturn('The Tyrannosaurus carnivorous dinosaur is 12 meters long');
    }

    /**
     * Checks carnivorous status of default dinosaur
     */
    function it_should_be_herbivore_by_default()
    {
        // Pick one of the below depending on if you like magic or not..
        // I hate magic... however.. lets explain how this works
        // This magic is handled by the object state matched
        /* @see \PhpSpec\Matcher\ObjectStateMatcher */
        // Essentially phpspec will do the following to handle this
        // - intercept methods starting with 'shouldBe' 'shouldNotBe'
        // - remove the 'shouldBe' 'shouldNotBe'
        // - prefix whats left with an 'is'
        //       Example: shouldNotBeCarnivorous => isCarnivorous
        // - check method exists, calls it
        // - validates returned boolean based on 'shouldBe' 'shouldNotBe'
        //       'shouldBe' expects true
        //       'shouldNotBe' expects false
        // ... i hate magic
        $this->shouldNotBeCarnivorous();

        // Non magic, easy understandbale, traceable method
        // $this->isCarnivorous()->shouldReturn(false);
    }

    /**
     * Checks carnivorous status is as expected for carnivores
     */
    function it_should_return_carnivores_are_carnivorous()
    {
        $this->beConstructedWith('Carnivore', true);
        $this->isCarnivorous()->shouldReturn(true);
    }

    /**
     * Checks carnivorous status is as expected for non carnivores
     */
    function it_should_return_non_carnivores_are_not_carnivorous()
    {
        $this->beConstructedWith('Herbivore', false);
        $this->isCarnivorous()->shouldReturn(false);
    }

    /**
     * Checks if other dinosaurs have the save diet as this one
     */
    function it_should_allow_to_check_if_other_dinosaurs_have_the_same_diet()
    {
        $this->beConstructedWith('Herbivore', false);

        // Create dinosaur for comparision
        $dinosaur = new Dinosaur('Carnivore', true);

        // This is the nice verbose traceable way of doing it (somewhat)
        //$this->hasSameDietAs($dinosaur)->shouldReturn(false);

        // There is however more magic that can be used here
        // This magic works very much the same way as shouldBe/shouldNotBe
        // How it works:
        // - intercept methods starting with 'shouldHave' 'shouldNotHave'
        // - remove the 'shouldHave' 'shouldNotHave'
        // - prefix whats left with a 'has'
        //       Example: shouldHaveSameDietAs => hasSameDietAs
        // - check method exists, calls it
        // - validates returned boolean based on 'shouldHave' 'shouldNotHave'
        //       'shouldHave' expects true
        //       'shouldNotHave' expects false
        // ... again ... i hate magic
        $this->shouldNotHaveSameDietAs($dinosaur);
    }

    /**
     * Checks dinosaurs have the same diet using stub
     *
     * @param \App\Entity\Dinosaur|\PhpSpec\Wrapper\Collaborator $dinosaur
     *     This magic is actually not too bad
     *     Essentially type hinting an object in the example method will result
     *     in a mock of that object being passed to it.. very clever, actually
     *     useful.
     */
    function it_should_allow_to_check_if_two_dinosaurs_have_same_diet_using_stub(Dinosaur $dinosaur)
    {
        // Because $dinosaur is passed a parameter this function it is actually
        // a mock object, below we define how methods will behave. In this
        // example, every time is carnivorous is called on the dinosaur it
        // will return false.
        //
        // Remember that by default all methods in a mock object will return
        // null.
        $dinosaur->isCarnivorous()->willReturn(false);

        // This will pass, as default dinosaur spec'd by this class is not
        // carnivorous, due to constructor method defaults
        $this->shouldHaveSameDietAs($dinosaur);
    }
}
