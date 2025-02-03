<?php
declare(strict_types=1);

namespace Saddemlabidi\ShopfinderGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Saddemlabidi\ShopfinderApi\Api\ShopRepositoryInterface;

class ShopByIdentifier implements ResolverInterface
{
    public function __construct(
        private ShopRepositoryInterface $shopRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {}

    /**
     * Resolve a shop by its identifier.
     *
     * @param Field $field
     * @param mixed $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|null
     * @throws GraphQlInputException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): ?array {
        if (empty($args['identifier'])) {
            throw new GraphQlInputException(__('The "identifier" argument is required.'));
        }

        $identifier = (string)$args['identifier'];

        // Build search criteria to filter by identifier
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('identifier', $identifier, 'eq')
            ->create();

        $searchResults = $this->shopRepository->getList($searchCriteria);
        $shops = $searchResults->getItems();

        if (empty($shops)) {
            throw new GraphQlInputException(__('No shop found for identifier "%1".', $identifier));
        }

        // Assuming identifiers are unique, return the first match.
        $shop = reset($shops);

        return [
            'id'                 => $shop->getId(),
            'identifier'         => $shop->getIdentifier(),
            'name'               => $shop->getName(),
            'country'            => $shop->getCountry(),
            'image'              => $shop->getImage(),
            'longitude_latitude' => $shop->getLongitudeLatitude(),
            'created_at'         => $shop->getCreatedAt(),
            'updated_at'         => $shop->getUpdatedAt()
        ];
    }
}
