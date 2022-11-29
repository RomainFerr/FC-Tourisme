<?php

namespace App\Controller;

use App\Repository\EtablissementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtablissementController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;

    /**
     * @param EtablissementRepository $etablissementRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
    }


    #[Route('/etablissements', name: 'app_etablissements')]
    public function getEtablissement(PaginatorInterface $paginator , Request $request): Response
    {
        $etablissements= $paginator->paginate($this->etablissementRepository->findBy(['actif'=>'true'],['nom'=>"ASC"]),
            $request->query->getInt('page', 1), 6 )/*limit per page*/;
        return $this->render('etablissement/index.html.twig', [
            'etablissements' => $etablissements,
        ]);
    }

    #[Route('/etablissement/{slug}', name: 'app_etablissement_slug')]
    public function getArticle($slug): Response
    {

        $etablissement = $this->etablissementRepository->findOneBy(["slug"=>$slug]);
        return $this->render('etablissement/etablissement.html.twig', [
            'etablissement' => $etablissement,
        ]);}


    }
