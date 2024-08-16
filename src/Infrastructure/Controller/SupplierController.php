<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;


use App\Domain\SaveFiles\SaveFilesInterface;
use App\Infrastructure\FileHandling\FileSymfonyAdapter;
use App\Infrastructure\Form\ImportFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SupplierController extends AbstractController
{
    #[Route('/supplier', name: 'app_supplier')]
    public function index(
        Request $request,
        SluggerInterface $slugger,
        SaveFilesInterface $saveFiles,
        #[Autowire('%kernel.project_dir%/var/imported/')] string $savedFiles
    ): Response {
        $form = $this->createForm(ImportFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $se = new FileSymfonyAdapter($request, $form, $slugger, $savedFiles);
            $supplier = $form->get('supplier')->getData();
            $dto = $se->getFileDTO();
            $saveFiles->saveFile($dto, $supplier);

            $this->addFlash('success', 'Fichier sauvegardé, tu peux le lancer dans la liste des imports');
            return $this->redirectToRoute('app_supplier');
        }

        return $this->render('supplier/index.html.twig', [
            'form' => $form,
        ]);
    }
}
