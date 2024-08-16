<?php

namespace App\Infrastructure\Controller;

use App\Domain\Entity\ImportFile;
use App\Domain\FileHandling\FromImportFile;
use App\Domain\ImportService\ImportService;
use App\Infrastructure\Form\ValidationFormType;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FileShowController extends AbstractController
{
    #[Route('/file/show/{id}', name: 'app_file_show')]
    public function index(
        ImportFile $importFile,
        Request $request,
        ImportService $importService,
        LoggerInterface $logger,
        #[Autowire('%kernel.project_dir%/var/imported/')] string $savedFiles
    ): Response {
        $validationForm = $this->createForm(ValidationFormType::class);
        $validationForm->handleRequest($request);

        if ($validationForm->isSubmitted() && $validationForm->isValid()) {
            $from = new FromImportFile($importFile);
            $dto = $from->getDto($savedFiles);
            try {
                $importService->import($dto, $importFile->getSupplier());
            } catch (ContainerExceptionInterface | NotFoundExceptionInterface $e) {
                dd($e->getMessage());
                $logger->error($e->getMessage());
            }
        }

        $dto = new FromImportFile($importFile);
        try {
            $content = $dto->getContentOf($savedFiles);
        } catch (Exception $e) {
            $logger->debug($e->getMessage());
            $content = [];
        }

        return $this->render('file_show/index.html.twig', [
            'results' => $content,
            'form' => $validationForm,
        ]);
    }
}
