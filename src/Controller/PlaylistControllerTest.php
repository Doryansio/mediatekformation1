<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Flex\Response;

/**
 * Description of PlaylistControllerTest
 *
 * @author Doryan
 */
class PlaylistControllerTest extends WebTestCase{

    private const nombrePlaylists = 27;
    
     public function testSortOnName(){
        $client = static::createClient();
        $client->request('GET', '/playlists/recherche/name');
        $client->submitForm('filtrer_playlist', ['recherche' => 'MCD']);
        $this->assertSelectorTextContains('h5',
                'Cours MCD MLD MPD');
    }

    public function testSortOnCategorie(){
        $client = static::createClient();
        $client->request('GET', '/playlists/recherche/id/categories');
        $client->submitForm('filtrer_categorie', ['recherche' => '7']);
        $this->assertSelectorTextContains('h5',
                "Bases de la programmation (C#)");
    }


    public function testSortOnTitleAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertCount(self::nombrePlaylists,$crawler->filter('h5'));
        $this->assertSelectorTextContains('h5',
                'Bases de la programmation (C#)');
    }

    public function testSortOnTitleDesc(){
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/DESC');
        $this->assertSelectorTextContains('h5',
                'Visual Studio 2019 et C#');
    }
     public function testAccessDetails(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink('Voir détail');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/13', $uri);
        $this->assertSelectorTextContains('h4',
                "Bases de la programmation (C#)");
    }
}
