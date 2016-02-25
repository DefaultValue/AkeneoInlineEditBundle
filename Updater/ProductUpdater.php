<?php

namespace DefaultValue\Bundle\AkeneoInlineEditBundle\Updater;

class ProductUpdater
{

    public function update($id, $attribute, $attributeValue, $dataLocale, $scopeCode)
    {
        $attributeHelper = $this->attributeHelper;
        $localizableAttributes = $attributeHelper->getLocalizableAttributes();
        $scopableAttributes = $attributeHelper->getScopableAttributes();
        $priceAttributes = $attributeHelper->getPriceAttributes();

        $locale = null;
        $scope = null;
        if (in_array($attribute, $localizableAttributes)) $locale = $dataLocale; // check if attribute localizable
        if (in_array($attribute, $scopableAttributes)) $scope = $scopeCode;; // check if attribute scopable
        if (in_array($attribute, $priceAttributes)) {
            $data = str_replace(" $", "", $attributeValue);
            $attributeValue = [[
                'data'      => $data,
                'currency'  => DVProductInterface::DEFAULT_PRODUCT_CURRENCY
            ]];
        }

        $product = $this->productRepository->getFullProduct($id);

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
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
