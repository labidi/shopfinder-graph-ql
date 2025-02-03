<?php
declare(strict_types=1);

namespace Saddemlabidi\ShopfinderGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Saddemlabidi\ShopfinderApi\Api\ShopRepositoryInterface;

class UpdateShop implements ResolverInterface
{
    public function __construct(
        private readonly ShopRepositoryInterface $shopRepository
    ) {}

    /**
     * Resolver for the updateShop mutation.
     *
     * @param Field $field
     * @param mixed $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws GraphQlInputException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        if (empty($args['input']['id'])) {
            throw new GraphQlInputException(__('Shop id must be specified.'));
        }

        $input = $args['input'];
        $shopId = (int)$input['id'];

        // Load the shop using the repository.
        try {
            $shop = $this->shopRepository->getById($shopId);
        } catch (\Exception $e) {
            throw new GraphQlInputException(__('Unable to find shop with id %1', $shopId));
        }

        if (isset($input['name'])) {
            $shop->setName((string)$input['name']);
        }
        if (isset($input['identifier'])) {
            $shop->setIdentifier((string)$input['identifier']);
        }
        if (isset($input['country'])) {
            $shop->setCountry((string)$input['country']);
        }
        if (isset($input['image'])) {
            $shop->setImage((string)$input['image']);
        }
        if (isset($input['longitude_latitude'])) {
            $shop->setLongitudeLatitude((string)$input['longitude_latitude']);
        }
        try {
            $this->shopRepository->save($shop);
        } catch (\Exception $e) {
            throw new GraphQlInputException(__('Unable to update shop: %1', $e->getMessage()));
        }
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
