<?php

namespace App\Service;

use App\Repository\ArticleRepository;

class ArticleService
{

  public function __construct(
    private readonly ArticleRepository $repo
  ){}
  
  //Récupérer les 6 derniers articles
  public function getLastest(int $max= 6){
    return $this->repo->findBy(['publie' => true],['created' => 'DESC'], $max);
  }
  
  // Récupérer les articles publiés
  public function articlePublish(){
    return $this->repo->findBy(['publie' => true]);
  }
 
  // Récuperer le nombre d'articles
  public function articleAll()
  {
    return $this->repo->findAll();
  }
  // Récuperer le nombre d'articles
  public function articleAllCount():int
  {
    return count($this->repo->findAll());
  }
  
  // Récuperer le nombre d'articles publiés
  public function articleAllPublish():int
  {
    return count($this->repo->findBy(['publie' => true]) );
  }
}