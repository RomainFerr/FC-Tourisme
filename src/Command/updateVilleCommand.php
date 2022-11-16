<?php

namespace App\Command;


use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class updateVilleCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private string $dataDirectory;
    protected SymfonyStyle $io;
    private VilleRepository $villeRepository;



    protected static $defaultName = 'app:import-villes-franche-comte';

    /**
     * @param EntityManagerInterface $entityManager
     * @param string $dataDirectory
     * @param VilleRepository $villeRepository
     */
    public function __construct(EntityManagerInterface $entityManager, string $dataDirectory, VilleRepository $villeRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->dataDirectory = $dataDirectory;
        $this->villeRepository = $villeRepository;
    }

    protected function configure()
    {
        $this->setDescription('Update les villes FC');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io=new SymfonyStyle($input, $output);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createVilles();
        return Command::SUCCESS;
    }

    private function getDataFromFile():array{
        $file= $this->dataDirectory . 'villes.csv';

        $fileExtension = pathinfo($file,PATHINFO_EXTENSION);

        $normalizers=[new ObjectNormalizer()];
        $context = [
            CsvEncoder::DELIMITER_KEY => ';',
            CsvEncoder::ENCLOSURE_KEY => '"',
            CsvEncoder::ESCAPE_CHAR_KEY => '\\',
            CsvEncoder::KEY_SEPARATOR_KEY => ',',
        ];
        $encoders=[ new CsvEncoder($context)];

        $serializer = new \Symfony\Component\Serializer\Serializer($normalizers,$encoders);
        /**@var string $fileString*/
        $fileString = file_get_contents($file);
        $data = $serializer->decode($fileString,$fileExtension);
        if (array_key_exists('results',$data)){
            return $data['results'];
        }
        return $data;
    }

    private function createVilles():void{
        $this->io->section('Creation des villes à partir du fichier');

        $villesCreated=0;

        foreach ($this->getDataFromFile() as $row) {

            if ($row['Département']==25 or $row['Département']==39 or $row['Département']==70 or $row['Département']==90){
                $ville = new Ville();
                $ville->setNom($row['Commune']." ".$row['Ancienne commune']); //ratacher le nom de l'ancienne commune
                $ville->setCodePostal($row['Code postal']);
                $ville->setNumDepartement($row['Département']);
                $ville->setNomDepartement($row['Nom département']);
                $ville->setNomRegion($row['Région']);

                $this->entityManager->persist($ville);

                $villesCreated++;
            }



        }
        $this->entityManager->flush();
        if ($villesCreated>1){
            $string="{villesCreated} villes créées dans la bdd";
        } else {
            $string ="Aucune ville créées";
        }
        $this->io->success($string);
    }
}