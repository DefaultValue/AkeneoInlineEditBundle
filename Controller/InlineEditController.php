<?php

namespace  DefaultValue\Bundle\AkeneoInlineEditBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

class InlineEditController extends Controller
{
    /**
     * Apply attribute value for given locale and scope(channel) after grid inline edit
     *
     * @AclAncestor("default_value_inline_edit_update_value")
     *
     * @param Request $request
     * @param $id
     * @param $dataLocale
     * @param $scopeCode
     * @return JsonResponse
     */
    public function updateAttributeValueAction(Request $request, $id, $dataLocale, $scopeCode)
    {
        $attributeCode = $request->query->get('attrName');
        $attributeValue = $request->query->get('attrVal');
        $productUpdater = $this->get('default_value.akeneo_inline_edit.updater.product_updater');

        $updated = $productUpdater->update($id, $attributeCode, $attributeValue, $dataLocale, $scopeCode);

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
