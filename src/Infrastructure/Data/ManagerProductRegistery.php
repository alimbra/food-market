<?php

declare(strict_types=1);

namespace App\Infrastructure\Data;

use App\Domain\Data\RegistryProductInterface;
use App\Domain\Dto\ProductDataFilterDTOInterface;
use App\Domain\Dto\ProductListCollectionDto;
use App\Domain\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

class ManagerProductRegistery implements RegistryProductInterface
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        return $this->entityManager->getRepository(Product::class)->findOneBy($criteria, $orderBy);
    }

    /**
     * @throws Exception
     */
    public function paginated(int $page, ?ProductDataFilterDTOInterface $filterDTO = null): ProductListCollectionDto
    {
        $productList = new ProductListCollectionDto();

        $pagination = new Paginator($this->buildQuery($filterDTO)->getQuery()
            ->setFirstResult(($page - 1) * self::LIMIT)
            ->setMaxResults(self::LIMIT)
            ->setHint(Paginator::HINT_ENABLE_DISTINCT, false), false);
        $productList->setProduct($pagination->getIterator());
        $productList->setCount($pagination->count());
        return $productList;
    }

    public function buildQuery(?ProductDataFilterDTOInterface $filterDTO = null): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Product::class)->createQueryBuilder('p')
            ->join('p.supplier', 's');

        return $this->addFilters($qb, $filterDTO);
    }

    protected function addFilters(QueryBuilder $qb, ?ProductDataFilterDTOInterface $filter = null): QueryBuilder
    {
        if (null === $filter) {
            return $qb;
        }

        $qb = $this->addFiltersName($qb, $filter->getName());

        return $this->addFiltersCode($qb, $filter->getCode());
    }

    protected function addFiltersName(QueryBuilder $qb, string $getName): QueryBuilder
    {
        return $qb->where('p.name LIKE :name')
            ->setParameter('name', '%' . $getName . '%');
    }

    private function addFiltersCode(QueryBuilder $qb, ?string $getCode): QueryBuilder
    {
        if ($getCode) {
            $qb->andWhere('p.code = :code')
                ->setParameter('code', $getCode);
        }

        return $qb;
    }
}
