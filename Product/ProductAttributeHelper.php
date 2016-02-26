<?php

namespace DefaultValue\Bundle\AkeneoInlineEditBundle\Product;

use DefaultValue\Bundle\AkeneoInlineEditBundle\ProductAttributesRepository;
use Pim\Bundle\CatalogBundle\Repository\AttributeRepositoryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Helper for working with attribute repository
 */
class ProductAttributeHelper
{
    /**
     * @var ProductAttributesRepository
     */
    private $attributeRepository;

    /**
     * @var EntityManager
     */
    public $em;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param EntityManager $em
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository,EntityManager $em)
    {
        $this->attributeRepository = $attributeRepository;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getPreserveAttributes()
    {
       $qb = $this->em->createQueryBuilder()
            ->select('attr')
            ->from('PimCatalogBundle:Attribute', 'attr')
            ->where('attr.code LIKE :preserve')
            ->setParameter(':preserve', '%preserve%')
            ->orderBy('attr.id', 'desc');

        return $this->prepareAttributesList($qb->getQuery()->getResult());
    }

    /**
     * @return array
     */
    public function getImportAttributes()
    {
        $qb = $this->em->createQueryBuilder()
            ->select('attr')
            ->from('PimCatalogBundle:Attribute', 'attr')
            ->where('attr.code LIKE :import')
            ->setParameter(':import', '%import%')
            ->orderBy('attr.id', 'desc');

        return $this->prepareAttributesList($qb->getQuery()->getResult());
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
     * Get from repository codes of attributes
     * @param $group_id int
     * @return array
     */
    public function getAttributesByGroupId($group_id)
    {
        $entities = $this->attributeRepository->findWithGroups([], ['conditions' => ['group' => $group_id]]);

        return $this->prepareAttributesList($entities);
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
