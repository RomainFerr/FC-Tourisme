<?php

namespace App\Controller;

use App\Repository\EtablissementRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;
    private VilleRepository $villeRepository;

    /**
     * @param EtablissementRepository $etablissementRepository
     * @param VilleRepository $villeRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository, VilleRepository $villeRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
        $this->villeRepository = $villeRepository;
    }


    #[Route('/', name: 'app_acceuil')]
    public function index(): Response
    {
        $ville90 = $this->villeRepository->findBy(['numDepartement' => 90]);
        $etablissements90 = $this->etablissementRepository->findBy(['ville' => $ville90]);
        $ville25 = $this->villeRepository->findBy(['numDepartement' => 25]);
        $etablissements25 = $this->etablissementRepository->findBy(['ville' => $ville25]);
        $ville70 = $this->villeRepository->findBy(['numDepartement' => 70]);
        $etablissements70 = $this->etablissementRepository->findBy(['ville' => $ville70]);
        $ville39 = $this->villeRepository->findBy(['numDepartement' => 39]);
        $etablissements39 = $this->etablissementRepository->findBy(['ville' => $ville39]);

shuffle($etablissements25);
shuffle($etablissements39);
shuffle($etablissements90);
shuffle($etablissements70);
        return $this->render('acceuil/index.html.twig', [
            'e25'=>$etablissements25,
            'e39'=>$etablissements39,
            'e90'=>$etablissements90,
            'e70'=>$etablissements70
        ]);
    }
}
