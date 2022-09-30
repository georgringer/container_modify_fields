<?php

declare(strict_types=1);

namespace GeorgRinger\ContainerModifyFields\Form\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProvider\AbstractItemProvider;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class Modify extends AbstractItemProvider implements FormDataProviderInterface
{
    /**
     * Resolve select items
     *
     * @param array $result
     * @return array
     * @throws \UnexpectedValueException
     */
    public function addData(array $result)
    {
        if ($result['tableName'] !== 'tt_content') {
            return $result;
        }

        $parentContainerRecordId = (int)($result['databaseRow']['tx_container_parent'][0] ?? 0);
        if ($parentContainerRecordId === 0) {
            return $result;
        }

        $parentContainerRow = BackendUtility::getRecord('tt_content', $parentContainerRecordId);
        if (!$parentContainerRow) {
            return $result;
        }


        $overrideConfiguration = $result['pageTsConfig']['TCEFORM.']['tt_content.']['container.'][$parentContainerRow['CType'] . '.'] ?? [];
        if (empty($overrideConfiguration)) {
            return $result;
        }

        $colPos = $result['databaseRow']['colPos'][0];

        $configurationOfCurrentColpos = $overrideConfiguration[$colPos . '.'] ?? [];
        $configurationOfAllColpos = $overrideConfiguration['_all.'] ?? [];
        ArrayUtility::mergeRecursiveWithOverrule($configurationOfAllColpos, $configurationOfCurrentColpos);


        $inlineCtype = $result['databaseRow']['CType'][0] ?? '';
        $configPerCtype = $configurationOfAllColpos[$inlineCtype . '.'] ?? [];
        $configPerCtypeAll = $configurationOfAllColpos['_all.'] ?? [];
        ArrayUtility::mergeRecursiveWithOverrule($configPerCtypeAll, $configPerCtype);

        if (empty($configPerCtypeAll)) {
            return $result;
        }

        $this->overlayConfiguration($result, $configPerCtypeAll);
        return $result;
    }

    protected function overlayConfiguration(array &$result, array $configuration): void
    {
        foreach ($configuration as $fieldName => $fieldConfiguration) {
            $fieldName = rtrim($fieldName, '.');
            if (!isset($result['processedTca']['columns'][$fieldName])) {
                continue;
            }

            if ((int)($fieldConfiguration['disabled'] ?? false) === 1) {
                unset($result['processedTca']['columns'][$fieldName]);
            }
            if ((int)($fieldConfiguration['required'] ?? false) === 1) {
                $eval = $result['processedTca']['columns'][$fieldName]['config']['eval'] ?? '';
                $eval .= ',required';
                $result['processedTca']['columns'][$fieldName]['config']['eval'] = $eval;
            }
            if (isset($fieldConfiguration['fixedItemValue']) &&
                array_key_exists('items', $result['processedTca']['columns'][$fieldName]['config'])) {
                $items =  $result['processedTca']['columns'][$fieldName]['config']['items'];
                foreach ($items as $key => $item) {
                    if ($item[1] != $fieldConfiguration['fixedItemValue']) {
                        unset($result['processedTca']['columns'][$fieldName]['config']['items'][$key]);
                    }
                }
            }
        }
    }
}
