<?php

namespace App\Service;

use App\Entity\Enclosure;
use App\Entity\Security;
use App\Factory\DinosaurFactory;

final class EnclosureBuilderService
{
    /**
     * @var \App\Factory\DinosaurFactory
     */
    private $dinosaurFactory;

    /**
     * @var \App\Service\EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * EnclosureBuilderService constructor.
     *
     * @param \App\Factory\DinosaurFactory $factory
     */
    public function __construct(DinosaurFactory $dinosaurFactory, EntityManagerInterface $entityManager)
    {
        $this->dinosaurFactory = $dinosaurFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * Builds an enclosure using a requested number of securities and
     * dinosaurs
     *
     * @param int $numSecurities
     * @param int $numDinosaurs
     *
     * @return \App\Entity\Enclosure
     */
    public function buildEnclosure(int $numSecurities, int $numDinosaurs): Enclosure
    {
        $enclosure = new Enclosure();

        $this->addSecuritySystems($numSecurities, $enclosure);
        $this->addDinosaurs($numDinosaurs, $enclosure);

        return $enclosure;
    }

    /**
     * Add security systems to a given enclosure
     *
     * @param int $numSecurities
     * @param \App\Entity\Enclosure $enclosure
     *
     * @return Enclosure
     */
    private function addSecuritySystems(int $numSecurities, Enclosure $enclosure): Enclosure
    {
        $securityNames = ['Fence', 'Electric fence', 'Guard tower', 'Helpful Sign'];
        for ($i = 0; $i < $numSecurities; $i++) {
            $securityName = $securityNames[array_rand($securityNames)];

            $security = new Security($securityName, true, $enclosure);
            $enclosure->addSecurity($security);
        }

        $this->entityManager->persist($enclosure);
        $this->entityManager->flush();

        return $enclosure;
    }

    /**
     * Adds dinosaurs to a given enclosure
     *
     * @param int $numDinosaurs
     * @param \App\Entity\Enclosure $enclosure
     *
     * @return Enclosure
     */
    private function addDinosaurs(int $numDinosaurs, Enclosure $enclosure): Enclosure
    {
        for ($i = 0; $i < $numDinosaurs; $i++) {
            $enclosure->addDinosaur(
                $this->dinosaurFactory->growVelociraptor(5 + $i)
            );
        }

        return $enclosure;
    }
}
