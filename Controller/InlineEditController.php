<?php

namespace  DefaultValue\Bundle\AkeneoInlineEditBundle\Controller;

use DefaultValue\Bundle\AkeneoInlineEditBundle\Updater\ProductUpdater;
use Pim\Bundle\CatalogBundle\Context\CatalogContext;
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

    /**
     * @var CatalogContext $contextConfigurator
     */
    private $catalogContext;

    public function __constructor(ProductUpdater $productUpdater, CatalogContext $catalogContext)
    {
        $this->productUpdater = $productUpdater;
        $this->catalogContext = $catalogContext;
    }

    /**
     * Apply attribute value for given locale and scope(channel) after grid inline edit
     *
     * @AclAncestor("default_value_inline_edit_update_value")
     *
     * @param Request $request
     * @param $id
     * @param $dataLocale
     * @return JsonResponse
     */
    public function updateAttributeValueAction(Request $request, $id, $dataLocale)
    {
        $attributeCode = $request->query->get('attrName');
        $attributeValue = $request->query->get('attrVal');
        $scopeCode = $this->catalogContext->getScopeCode();

        $updated = $this->productUpdater->update($id, $attributeCode, $attributeValue, $dataLocale, $scopeCode);

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
