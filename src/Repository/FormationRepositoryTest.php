<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Description of FormationRepositoryTest
 *
 * @author Doryan
 */
class FormationRepositoryTest extends KernelTestCase{
    
    /**
     * Récupère le repository de Formation
     * @return FormationRepository
     */
    public function recupRepository(): FormationRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }
    
    /**
     * Création d'une instance de Formation
     * @return FormationRepository
     */
    public function newFormation(): Formation{
        $publishedAt = new DateTime('2020-01-01');
        $formation = (new Formation())
                ->setPublishedAt($publishedAt)
                ->setTitle('test')
                ->setVideoId('0');
        return $formation;
    }
    
    public function testAddFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations+1, $repository->count([]), 
                "erreur lors de l'ajout");
    }

    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]),
                "erreur lors de la suppression");
    }
}