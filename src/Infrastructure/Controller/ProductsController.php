<?php

namespace App\Infrastructure\Controller;

use App\Domain\Data\RegistryProductInterface;
use App\Infrastructure\Dto\FromDataFormToFilterDTO;
use App\Infrastructure\Form\SearchProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(Request $request, RegistryProductInterface $registryProduct): Response
    {
        $page = $request->query->getInt('page', 1);
        $searchForm = $this->createForm(SearchProductFormType::class);
        $searchForm->handleRequest($request);

        $filter = new FromDataFormToFilterDTO($request);
        $result = $registryProduct->paginated($page, $filter);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $filter = new FromDataFormToFilterDTO($request);
            $result = $registryProduct->paginated($page, $filter);

            return $this->render(
                'products/index.html.twig',
                [
                    'products' => $result->getIterator(),
                    'page' => 1,
                    'max' => $result->getMax(),
                    'form' => $searchForm,
                ]
            );
        }

        return $this->render('products/index.html.twig', [
            'products' => $result->getIterator(),
            'page' => $page,
            'max' => $result->getMax(),
            'form' => $searchForm,
        ]);
    }
}
