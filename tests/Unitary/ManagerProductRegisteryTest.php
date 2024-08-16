<?php

declare(strict_types=1);

namespace App\Tests\Unitary;

use App\Domain\Dto\ProductListCollectionDto;
use App\Domain\Entity\Supplier;
use App\Infrastructure\Data\ManagerProductRegistery;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ManagerProductRegisteryTest extends KernelTestCase
{
    /**
     * @throws Exception
     */

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::bootKernel();

        $em = self::getContainer()->get(EntityManagerInterface::class);
        self::purgeAll($em);

        $supplier = new Supplier();
        $supplier->setName('supplier');
        $em->persist($supplier);
        $em->flush();
        $products = new DummyProducts($supplier);
        foreach ($products->getProducts() as $product) {
            $em->persist($product);
        }
        $em->flush();
    }

    /**
     * @throws Exception
     */
    public function testPaginatedWithFiltersByCode(): void
    {
        $service = self::getContainer()->get(ManagerProductRegistery::class);

        self::assertInstanceOf(ManagerProductRegistery::class, $service);

        $filter = new DummyProductDataFilterDTO();

        $result = $service->paginated(1, $filter);

        self::assertInstanceOf(ProductListCollectionDto::class, $result);

        self::assertSame(1, $result->count());
    }

    public function testPaginatedWithFilterWithoutFilters(): void
    {
        $service = self::getContainer()->get(ManagerProductRegistery::class);

        self::assertInstanceOf(ManagerProductRegistery::class, $service);


        $result = $service->paginated(1);

        self::assertInstanceOf(ProductListCollectionDto::class, $result);

        self::assertSame(50, $result->count());
        self::assertSame(5.0, $result->getMax());
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public static function purgeAll(EntityManagerInterface $em): void
    {
        $em->getConnection()
            ->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        foreach (['import_file', 'product', 'supplier'] as $tableName) {
            $em->getConnection()
                ->executeQuery("TRUNCATE TABLE `{$tableName}`");
        }
        $em->getConnection()
            ->executeQuery('SET FOREIGN_KEY_CHECKS = 1');
    }
}
