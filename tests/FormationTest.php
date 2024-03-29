<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormationTest
 *
 * @author Doryan
 */
class FormationTest extends TestCase{

    public function testGetPublishedAtString(){
        $formation = new Formation();
        $publishedAt = new DateTime("2020-01-01");
        $formation->setPublishedAt($publishedAt);
        $this->assertEquals("01/01/2020", $formation->getPublishedAtString());
    }

}
