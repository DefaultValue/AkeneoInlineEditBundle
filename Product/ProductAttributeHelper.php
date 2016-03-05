<?php

namespace DefaultValue\Bundle\AkeneoInlineEditBundle\Product;

use Pim\Component\Catalog\Repository\AttributeRepositoryInterface;
use Pim\Bundle\CatalogBundle\Doctrine\ORM\Repository\AttributeRepository;
use Doctrine\ORM\EntityManager;

/**
 * Helper for working with attribute repository
 */
class ProductAttributeHelper
{
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    /**
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Get from repository codes of scopable attributes
     * @return array
     */
    public function getScopableAttributes()
    {
        $scopableEntities = $this->attributeRepository->findByScopable(1);

        return $this->prepareAttributesList($scopableEntities);
    }

    /**
     * Get from repository codes of localizable attributes
     * @return array
     */
    public function getLocalizableAttributes()
    {
        $localizableEntities = $this->attributeRepository->findByLocalizable(1);

        return $this->prepareAttributesList($localizableEntities);
    }

    /**
     * Get from repository codes of price attributes
     * @return array
     */
    public function getPriceAttributes()
    {
        $attributeType = 'pim_catalog_price_collection'; // attribute type for prices in database
        $priceEntities = $this->attributeRepository->findByAttributeType($attributeType);

        return $this->prepareAttributesList($priceEntities);
    }

    /**
     * @param $attributesCollection
     * @return array
     */
    private function prepareAttributesList($attributesCollection)
    {
        $attributes = [];

        /**
         * @var \Pim\Bundle\CatalogBundle\Entity\Attribute $entity
         */
        foreach ($attributesCollection as $entity) {
            $attributes[] = $entity->getCode();
        }

        return $attributes;
    }
}
