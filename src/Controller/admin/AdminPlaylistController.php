<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\admin;


use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistController
 *
 * @author Doryan
 */
class AdminPlaylistController extends AbstractController {
   public const PADMIN = "admin/admin.playlists.html.twig";
    
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;    
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     *@Route("admin/playlists", name="admin.playlists")
     *@return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render( self::PADMIN, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    /**
     * @Route("admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        switch ($champ){
            case"name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository>findAllOrderByNbFormations($ordre);
                break;  
            default;
        }
        
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }         
    
    /**
     * @Route("admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PADMIN, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("/admin/playlist/{id}/suppr", name="admin.playlists.suppr")
     * @param type $id
     * @return Response
    */
    public function suppr($id, Request $request): Response{
        try{
            if ($this ->isCsrfTokenValid('suppr'. $id, $request -> get('_token'))){
           $playlist = $this->playlistRepository->find($id);
           $this->playlistRepository->remove($playlist, true);
            }
            return $this->redirectToRoute('admin.playlists');
        } catch(\Exception $e) {
            $this->addFlash('playlist_request', 'Impossible de supprimer la playlist car elle contient des formations');
            return $this->redirectToRoute('admin.playlists');
        }
             
    }
    
     /**
     * @Route("/admin/playlist/{id}", name="admin.playlists.edit")
     * @param Type $id
     * @return Response
    */
    public function edit($id, Request $request): Response {
        $playlist = $this->playlistRepository->find($id);
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute("admin.playlists");
            
        }
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("admin/admin.playlist.ajout.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
            'formPlaylist' => $formPlaylist->createView()
        ]);
    }
    
    /**
     * @Route("/admin/playlist", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
    */
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted()&& $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute("admin.playlists");
        }
        return $this->render("admin/Admin.playlist.ajout.html.twig", [
            'playlist' => $playlist,
            'formPlaylist' => $formPlaylist->createView()
        ]);
    }
    
}

