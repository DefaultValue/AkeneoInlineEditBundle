<?php

namespace  DefaultValue\Bundle\AkeneoInlineEditBundle\Controller;

use DefaultValue\Bundle\AkeneoInlineEditBundle\Updater\ProductUpdater;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

class InlineEditController
{
    /**
     * @var ProductUpdater $productUpdater
     */
    private $productUpdater;

    public function __constructor(ProductUpdater $productUpdater)
    {
        $this->productUpdater = $productUpdater;
    }

    /**
     * Apply attribute value for given locale and scope(channel) after grid inline edit
     *
     * @AclAncestor("default_value_inline_edit_update_value")
     *
     * @param Request $request
     * @param $productId
     * @param $attributeCode
     * @param $dataLocale
     * @param $scopeCode
     * @param $attributeValue
     * @return JsonResponse
     */
    public function updateAttributeValue(Request $request, $productId, $attributeCode, $dataLocale, $scopeCode, $attributeValue)
    {
        $updated = $this->productUpdater->update($productId, $attributeCode, $attributeValue, $dataLocale, $scopeCode);

        $message = $updated ? sprintf('Product "%s" %s', $attributeCode, 'attribute value successfully changed') : sprintf('Product "%s" %s', $attributeCode, 'attribute value wasn\'t changed');
        $response = [
            'successful' => $updated,
            'message'    => $message
        ];

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($response);
        } else {
            return new RedirectResponse('pim_enrich_product_index');
        }
    }
}
