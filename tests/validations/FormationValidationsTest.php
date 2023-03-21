<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Tests\validations;


use App\Entity\Formation;
use App\Entity\Playlist;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author Doryan
 */
class FormationValidationsTest extends KernelTestCase {

    public function getFormation(string $publishedAtDate, string $title): Formation{
        $publishedAt = new DateTime($publishedAtDate);
        $playlist = (new Playlist())
                ->setName('test');
        return (new Formation())
                ->setPublishedAt($publishedAt)
                ->setTitle($title)
                ->setVideoId('0')
                ->setPlaylist($playlist);
    }

    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }

    public function testValidPublishedAt(){
        //La date doit être antérieure à la date du jour pour être valide
        $formation = $this->getFormation('2020-01-01', 'test');
        $this->assertErrors($formation, 0);
    }

    public function testNonValidPublishedAt(){
        //La date doit être postérieure à la date du jour pour être invalide
        $formation = $this->getFormation('2050-01-01', 'test');
        $this->assertErrors($formation, 0);
    }
}
