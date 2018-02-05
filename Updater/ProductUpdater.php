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

    /** @var ProductAttributeHelper */
    private $attributeHelper;

    /** @var ProductRepository */
    private $productRepository;

    /** @var PropertySetterInterface */
    private $productPropertySetter;

    /** @var ProductSaver */
    private $productSaver;

    /** @var string */
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
     * @param $attrCode
     * @param $attrValue
     * @param $attrLocale
     * @param $attrScope
     * @return bool
     */
    public function update($productId, $attrCode, $attrValue, $attrLocale, $attrScope)
    {
        $isSuccess = false;
        $attributeHelper = $this->attributeHelper;

        $locale = in_array($attrCode, $attributeHelper->getLocalizableAttributes(), true) ?  $attrLocale : null;
        $scope  = in_array($attrCode, $attributeHelper->getScopableAttributes(), true) ? $attrScope : null;

        $attrValue = $this->prepareAttributeValue($attrCode, $attrValue);
        $product   = $this->productRepository->getFullProduct($productId);

        try {
            $this->productPropertySetter->setData(
                $product,
                $attrCode,
                $attrValue,
                [
                    'locale' => $locale,
                    'scope'  => $scope
                ]
            );

            $this->productSaver->save($product);
            $this->setUpdateInfo("{$product->getIdentifier()} product '{$attrCode}' changed successfully");
            $isSuccess = true;
        } catch (\Exception $e) {
            $this->setUpdateInfo("Something went wrong. {$product->getIdentifier()} product '{$attrCode}' was not changed");
        }

        return $isSuccess;
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
        if (in_array($attribute, $priceAttributes, true)) {
            $data = str_replace(['$', ',', ' '], '', $attributeValue);
            $attributeValue = [[
                'amount'   => $data,
                'currency' => static::DEFAULT_PRODUCT_CURRENCY
            ]];
        }

        return $attributeValue;
    }
}
