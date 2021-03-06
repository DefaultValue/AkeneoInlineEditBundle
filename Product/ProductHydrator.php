<?php

namespace DefaultValue\Bundle\AkeneoInlineEditBundle\Product;

use Oro\Bundle\DataGridBundle\Datasource\ResultRecord;
use Pim\Bundle\DataGridBundle\Datasource\ResultRecord\HydratorInterface;

/**
 * Hydrate results of Doctrine ORM query as ResultRecord array, with extra scopeCode property
 *
 */
class ProductHydrator implements HydratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($qb, array $options = [])
    {
        $localeCode = $options['locale_code'];
        $scopeCode = $options['scope_code'];
        $query = $qb->getQuery();
        $results = $query->getArrayResult();
        $rows    = [];
        foreach ($results as $result) {
            $entityFields = [];
            if (isset($result[0])) {
                $entityFields = $result[0];
                unset($result[0]);
            }
            $otherFields = $result;
            $result = $entityFields + $otherFields;
            $result = $this->prepareValues($result);
            $result = $this->prepareGroups($result);
            $result['dataLocale'] = $localeCode;
            $result['scopeCode'] = $scopeCode;

            $rows[] = new ResultRecord($result);
        }

        return $rows;
    }

    /**
     * Prepare product values
     *
     * @param array $result
     *
     * @return array
     */
    protected function prepareValues($result)
    {
        if (isset($result['values'])) {
            $values = $result['values'];
            foreach ($values as $value) {
                $result[$value['attribute']['code']] = $value;
            }
            unset($result['values']);
        }

        return $result;
    }

    /**
     * Prepare product groups
     *
     * @param array $result
     *
     * @return array
     */
    protected function prepareGroups($result)
    {
        if (isset($result['groups'])) {
            $groups = [];
            foreach ($result['groups'] as $group) {
                $code = $group['code'];
                $label = (count($group['translations']) > 0) ? $group['translations'][0]['label'] : null;
                $groups[$code] = ['code' => $code, 'label' => $label];
            }
            $result['groups'] = $groups;
        }

        return $result;
    }
}
