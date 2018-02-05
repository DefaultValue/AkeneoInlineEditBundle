<?php

namespace  DefaultValue\Bundle\AkeneoInlineEditBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function updateAttributeValueAction(Request $request, $id, $dataLocale, $scopeCode): JsonResponse
    {
        $attrCode       = $request->get('code');
        $attrValue      = $request->get('value');
        $productUpdater = $this->get('default_value.akeneo_inline_edit.updater.product_updater');

        $status = $productUpdater->update($id, $attrCode, $attrValue, $dataLocale, $scopeCode);

        $this->json([
            'successful' => $status,
            'message'    => $productUpdater->getUpdateInfo()
        ]);
    }
}
