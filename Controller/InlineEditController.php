<?php

namespace  DefaultValue\Bundle\AkeneoInlineEditBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class InlineEditController
{
    /**
     * Apply attribute value for given locale and scope(channel) after grid inline edit
     *
     * @param Request $request
     * @param $productId
     * @param $dataLocale
     * @param $scopeCode
     * @return JsonResponse|RedirectResponse
     */
    public function applyAttributeValueAction(Request $request, $productId, $dataLocale, $scopeCode)
    {
    }
}
