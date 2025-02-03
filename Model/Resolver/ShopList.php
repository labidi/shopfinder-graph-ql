<?php
declare(strict_types=1);

namespace Saddemlabidi\ShopfinderGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Saddemlabidi\Shopfinder\Api\ShopRepositoryInterface;

class ShopList implements ResolverInterface
{
    public function __construct(
        private readonly ShopRepositoryInterface $shopRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {}

    /**
     * Resolves the GraphQL query to fetch all shops.
     *
     * @param Field $field
     * @param mixed $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        // Create an empty search criteria to fetch all shops
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->shopRepository->getList($searchCriteria);

        $shops = [];
        foreach ($searchResults->getItems() as $shop) {
            $shops[] = [
                'id'                  => $shop->getId(),
                'identifier'          => $shop->getIdentifier(),
                'name'                => $shop->getName(),
                'description'         => $shop->getDescription(),
                'country'             => $shop->getCountry(),
                'image'               => $shop->getImage(),
                'longitude_latitude'  => $shop->getLongitudeLatitude(),
                'created_at'          => $shop->getCreatedAt(),
                'updated_at'          => $shop->getUpdatedAt()
            ];
        }
        return $shops;
    }
}
