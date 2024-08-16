<?php

namespace App\Infrastructure\Controller;

use App\Domain\Data\RegistryImportFileSaveInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ManualImportController extends AbstractController
{
    #[Route('/manual/import', name: 'app_manual_import')]
    public function index(RegistryImportFileSaveInterface $registryImportFileSave): Response
    {
        $results = $registryImportFileSave->findAll();

        return $this->render('manual_import/index.html.twig', [
            'imports' => $results,
        ]);
    }
}
