<?php

namespace App\Entity;

/**
 * Class Dinosaur
 *
 * @package App\Entity
 */
class Dinosaur
{
    /**
     * The genus of the dinosaur
     *
     * @var string
     */
    protected string $genus;

    /**
     * Is the dinosaur a carnivore?
     *
     * @var bool
     */
    protected bool $isCarnivorous;

    /**
     * Dinosaurs length
     *
     * @var int
     */
    protected int $length = 0;

    /**
     * Dinosaur constructor.
     *
     * @param string $genus
     * @param bool $isCarnivorous
     */
    public function __construct(string $genus = 'Unknown', bool $isCarnivorous = false)
    {
        $this->genus = $genus;
        $this->isCarnivorous = $isCarnivorous;
    }

    /**
     * Returns the description of the dinosaur
     *
     * @return string
     */
    public function getDescription(): string
    {
        return sprintf(
            'The %s %s dinosaur is %d meters long',
            $this->genus,
            $this->isCarnivorous ? 'carnivorous' : 'non-carnivorous',
            $this->length
        );
    }

    /**
     * Returns the genus/type of the dinosaur
     *
     * @return string
     */
    public function getGenus(): string
    {
        return $this->genus;
    }

    /**
     * Returns the dinosaurs length
     *
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Sets the length of the dinosaur
     *
     * @param int $length
     */
    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    /**
     * Returns whether the dinosaur is a carnivore or not
     *
     * @return bool
     */
    public function isCarnivorous(): bool
    {
        return $this->isCarnivorous;
    }

    /**
     * Determines if this dinosaur has the same diet as other dinosaurs
     * passed to it
     *
     * @param \App\Entity\Dinosaur $dinosaur
     *     The dinosaur to use when comparing diets.
     *     In its own parameter as this method requires atleast one dinosaur
     *     to be passed
     * @param \App\Entity\Dinosaur ...$otherDinosaurs
     *     A dynamic number of other dinosaurs to compare te diets for.
     *
     * @return bool
     *     Do the diets match?
     *     If multiple dinosaurs are passed to this method all must have the
     *     same diet for this method to return true.
     */
    public function hasSameDietAs(Dinosaur $dinosaur, Dinosaur ...$otherDinosaurs): bool
    {
        $dinosaursToCheck = array_merge([$dinosaur], $otherDinosaurs);

        foreach ($dinosaursToCheck as $dinosaurBeingChecked) {
            if ($dinosaurBeingChecked->isCarnivorous() !== $this->isCarnivorous()) {
                return false;
            }
        }

        return true;
    }
}
