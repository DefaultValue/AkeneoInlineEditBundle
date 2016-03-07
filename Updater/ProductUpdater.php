<?php

namespace DefaultValue\Bundle\AkeneoInlineEditBundle\Updater;

use Pim\Bundle\CatalogBundle\Doctrine\ORM\Repository\ProductRepository;
use Akeneo\Component\StorageUtils\Updater\PropertySetterInterface;
use Pim\Bundle\CatalogBundle\Doctrine\Common\Saver\ProductSaver;
use DefaultValue\Bundle\AkeneoInlineEditBundle\Product\ProductAttributeHelper;

/**
 * Service which updates product attribute value
 */
class ProductUpdater
{
    const DEFAULT_PRODUCT_CURRENCY = 'USD';

    /**
     * @var ProductAttributeHelper
     */
    private $attributeHelper;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var PropertySetterInterface
     */
    private $productPropertySetter;

    /**
     * @var ProductSaver
     */
    private $productSaver;

    /**
     * @var
     */
    private $updateInfo;

    /**
     * @param ProductAttributeHelper  $attributeHelper
     * @param ProductRepository       $productRepository
     * @param PropertySetterInterface $productPropertySetter
     * @param ProductSaver            $productSaver
     */
    public function __construct(
        ProductAttributeHelper $attributeHelper,
        ProductRepository $productRepository,
        PropertySetterInterface $productPropertySetter,
        ProductSaver $productSaver
    )
    {
        $this->attributeHelper       = $attributeHelper;
        $this->productRepository     = $productRepository;
        $this->productPropertySetter = $productPropertySetter;
        $this->productSaver          = $productSaver;
    }

    /**
     * Update product attribute value
     *
     * @param $productId
     * @param $attribute
     * @param $attributeValue
     * @param $dataLocale
     * @param $scopeCode
     * @return bool
     */
    public function update($productId, $attribute, $attributeValue, $dataLocale, $scopeCode)
    {
        $attributeHelper = $this->attributeHelper;
        $localizableAttributes = $attributeHelper->getLocalizableAttributes();
        $scopableAttributes = $attributeHelper->getScopableAttributes();

        $locale = null;
        $scope = null;
        if (in_array($attribute, $localizableAttributes)) $locale = $dataLocale; // check if attribute localizable
        if (in_array($attribute, $scopableAttributes)) $scope = $scopeCode; // check if attribute scopable

        $attributeValue = $this->prepareAttributeValue($attribute, $attributeValue);
        $product = $this->productRepository->getFullProduct($productId);

        try {
            $this->productPropertySetter->setData( // set attribute value
                $product,
                $attribute,
                $attributeValue,
                [
                    'locale' => $locale,
                    'scope'  => $scope
                ]
            );

            $this->productSaver->save($product);
            $updateInfo = sprintf('Product "%s" %s', $attribute, 'attribute value is successfully changed');
            $this->setUpdateInfo($updateInfo);
        } catch (\Exception $e) {
            $updateInfo = sprintf('Product "%s" %s. %s', $attribute, 'attribute value wasn\'t changed', $e->getMessage());
            $this->setUpdateInfo($updateInfo);
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getUpdateInfo()
    {
        return $this->updateInfo;
    }

    /**
     * @param mixed $updateInfo
     */
    public function setUpdateInfo($updateInfo)
    {
        $this->updateInfo = $updateInfo;
    }

    /**
     * @param $attribute
     * @param $attributeValue
     * @return array
     */
    protected function prepareAttributeValue($attribute, $attributeValue)
    {
        $priceAttributes = $this->attributeHelper->getPriceAttributes();
        if (in_array($attribute, $priceAttributes)) {
            $data = str_replace(" $", "", $attributeValue);
            $attributeValue = [[
                'data'      => $data,
                'currency'  => static::DEFAULT_PRODUCT_CURRENCY
            ]];
        }

        return $attributeValue;
    }
}
