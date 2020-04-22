<?php

namespace App\Entity;

use App\Exception\DinosaursAreRunningRampantException;
use App\Exception\NotABuffetException;

/**
 * Class Enclosure
 *
 * @package App\Entity
 */
final class Enclosure
{
    /**
     * The dinosaurs stored within the enclosure
     *
     * @var \App\Entity\Dinosaur[]
     */
    private $dinosaurs = [];

    /**
     * The securities set against the enclosure
     *
     * @var array
     */
    private $securities = [];

    /**
     * Enclosure constructor.
     */
    public function __construct(bool $withBasicSecurity = false, Dinosaur ...$dinosaurs)
    {
        if ($withBasicSecurity) {
            $this->addSecurity(new Security('Fence', true, $this));
        }

        foreach ($dinosaurs as $dinosaur) {
            $this->addDinosaur($dinosaur);
        }
    }

    /**
     * Returns the dinosaurs within the enclosure
     *
     * @return array
     */
    public function getDinosaurs(): array
    {
        return $this->dinosaurs;
    }

    /**
     * Adds a dinosaur to the enclosure
     *
     * @param \App\Entity\Dinosaur $dinosaur
     *
     * @return self
     */
    public function addDinosaur(Dinosaur $dinosaur): self
    {
        if ($this->isSecurityActive() === false) {
            throw new DinosaursAreRunningRampantException('Are you crazy!?');
        }

        if ($this->canAddDinosaur($dinosaur) === false) {
            throw new NotABuffetException();
        }

        $this->dinosaurs[] = $dinosaur;

        return $this;
    }

    /**
     * Determines if a dinosaur can be added to the enclosure
     *
     * @param \App\Entity\Dinosaur $dinosaur
     *
     * @return bool
     */
    private function canAddDinosaur(Dinosaur $dinosaur): bool
    {
        // First dinosaur in the enclosure? Come on it!
        if (count($this->dinosaurs) === 0) {
            return true;
        }

        // Dinosaurs already in enclosure, ensure they have the same diet as the
        // one we're trying to add?
        return $dinosaur->hasSameDietAs(...$this->dinosaurs);
    }

    /**
     * Adds a security system to the enclosure
     *
     * @param \App\Entity\Security $security
     */
    public function addSecurity(Security $security)
    {
        $this->securities[] = $security;
    }

    /**
     * Determines if security is active for this enclosure
     *
     * @return bool
     */
    public function isSecurityActive(): bool
    {
        foreach ($this->securities as $security) {
            if ($security->getIsActive()) {
                return true;
            }
        }

        return false;
    }
}
