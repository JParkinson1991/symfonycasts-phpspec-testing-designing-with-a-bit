<?php

namespace App\Factory;

use App\Entity\Dinosaur;

/**
 * Class DinosaurFactory
 *
 * @package App\Factory
 */
class DinosaurFactory
{
    /**
     * Grows and returns a velociraptor
     *
     * @param int $length
     *     The length of the velociraptor to grow
     *
     * @return \App\Entity\Dinosaur
     */
    public function growVelociraptor(int $length): Dinosaur
    {
        return $this->createDinosaur(
            'Velociraptor',
            true,
            $length
        );
    }

    /**
     * Creates and returns a dinosaur
     *
     * @param string $genus
     * @param bool $isCarnivorous
     * @param int $length
     *
     * @return \App\Entity\Dinosaur
     */
    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);

        return $dinosaur;
    }
}
