<?php

namespace DefaultValue\Bundle\AkeneoInlineEditBundle\Product;

use Pim\Bundle\CatalogBundle\Doctrine\ORM\Repository\AttributeRepository;

/**
 * Query from database product attributes
 */
class ProductAttributesRepository extends AttributeRepository
{
    /**
     * Find attributes by `scopable` attribute. By default fetches scopable attributes
     *
     * @param int $scopable
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByScopable($scopable = 1)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('attr')
            ->from('PimCatalogBundle:Attribute', 'attr')
            ->where('attr.scopable = :scopable')
            ->setParameter(':scopable', $scopable)
            ->orderBy('attr.id', 'desc');

        return $result;
    }

    /**
     * Find attributes by `localizable` attribute. By default fetches localizable attributes
     *
     * @param int $localizable
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByLocalizable($localizable = 1)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('attr')
            ->from('PimCatalogBundle:Attribute', 'attr')
            ->where('attr.localizable = :localizable')
            ->setParameter(':localizable', $localizable)
            ->orderBy('attr.id', 'desc');

        return $result;
    }

    /**
     * Find attributes by attributeType.
     *
     * @param string $attributeType
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByAttributeType($attributeType)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select()
            ->from('PimCatalogBundle:Attribute', 'attr')
            ->where('attr.attributeType = :attributeType')
            ->setParameter(':attributeType', $attributeType);

        return $result;
    }
}
