<?php

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\GeorgRinger\ContainerModifyFields\Form\FormDataProvider\Modify::class] = [
    'depends' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\PageTsConfigMerged::class,
    ],
];
