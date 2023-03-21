<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Flex\Response;

/**
 * Description of FormationControllerTest
 *
 * @author Doryan
 */
class FormationControllerTest extends WebTestCase{

    private const nombreFormations = 236;
    
     public function testSortOnTitle(){
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/title');
        $client->submitForm('filtrer_formation', ['recherche' => 'de']);
        $this->assertSelectorTextContains('h5',
                'Eclipse n°8 : Déploiement');
    }

    public function testSortOnPlaylist(){
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/name/playlist');
        $client->submitForm('filtrer_playlist', ['recherche' => 'de']);
        $this->assertSelectorTextContains('h5',
                'Cours Programmation Objet');
    }

    public function testSortOnCategorie(){
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/id/categories');
        $client->submitForm('filtrer_categorie', ['recherche' => '7']);
        $this->assertSelectorTextContains('h5',
                "C# : Sérialisation d'objets");
    }

    public function testSortOnTitleAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertCount(self::nombreFormations,$crawler->filter('h5'));
        $this->assertSelectorTextContains('h5',
                'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    public function testSortOnTitleDesc(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/DESC');
        $this->assertSelectorTextContains('h5',
                'UML : Diagramme de paquetages');
    }

    public function testSortOnPlaylistAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertCount(self::nombreFormations,$crawler->filter('h5'));
        $this->assertSelectorTextContains('h5',
                'Bases de la programmation n°74 - POO : collections');
    }

     public function testSortOnPlaylistDesc(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('h5',
                'C# : ListBox en couleur');
    }

    public function testSortOnDateAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/publishedAt/ASC');
        $this->assertCount(self::nombreFormations,$crawler->filter('h5'));
        $this->assertSelectorTextContains('h5',
                "Cours UML (1 à 7 / 33) : introduction et cas d'utilisation");
    }

    public function testSortOnDateDesc(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertSelectorTextContains('h5',
                'Eclipse n°8 : Déploiement');
    }
    
     public function testAccessDetails(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('miniature');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);
        $this->assertSelectorTextContains('h4',
                "Eclipse n°8 : Déploiement");
    }

}

