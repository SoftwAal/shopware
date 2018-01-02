<?php declare(strict_types=1);

namespace Shopware\DbalIndexing\Common;

use Shopware\Api\Entity\Search\Criteria;
use Shopware\Api\Entity\Search\Sorting\FieldSorting;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Api\Shop\Repository\ShopRepository;
use Shopware\Api\Shop\Struct\ShopBasicStruct;

class ContextVariationService
{
    /**
     * @var ShopRepository
     */
    private $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    /**
     * @return TranslationContext[]
     */
    public function createContexts(): array
    {
        $context = TranslationContext::createDefaultContext();
        $criteria = new Criteria();
        $criteria->addSorting(new FieldSorting('shop.is_default'));
        $criteria->addSorting(new FieldSorting('shop.parent_uuid'));
        $shops = $this->shopRepository->search(new Criteria(), $context);

        return $shops->map(function (ShopBasicStruct $shop) {
            return TranslationContext::createFromShop($shop);
        });
    }
}