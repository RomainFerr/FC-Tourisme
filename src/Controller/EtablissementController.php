<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Repository\EtablissementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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




#[Route('/favoris/ajout/{id}', name: 'app_ajout_favoris')]
    public function ajoutFavoris(Etablissement $etablissement, EntityManagerInterface $manager): Response
{
if (!$etablissement){
    throw new NotFoundHttpException( "Pas d'établissement trouvé");
}
    $etablissement->addFavori($this->getUser());

$manager->persist($etablissement);
$manager->flush();
   return $this->redirectToRoute('app_etablissements');

}

    #[Route('/favoris/remove/{id}', name: 'app_remove_favoris')]
    public function removeFavoris(Etablissement $etablissement, EntityManagerInterface $manager): Response
    {
        if (!$etablissement){
            throw new NotFoundHttpException( "Pas d'établissement trouvé");
        }
        $etablissement->removeFavori($this->getUser());

        $manager->persist($etablissement);
        $manager->flush();

        return $this->redirectToRoute('app_etablissements');
    }



    #[Route('/etablissements/favoris', name: 'app_etablissement_favoris')]
    public function getArticleFavoris(UserRepository $userRepository,PaginatorInterface $paginator , Request $request): Response
    {
        $user = $userRepository->find(["id"=>$this->getUser()]);
        $etablissements =$user->getFavoris();
        return $this->render('etablissement/favoris.html.twig', [
            'etablissements' => $etablissements,
        ]);
    }



}

