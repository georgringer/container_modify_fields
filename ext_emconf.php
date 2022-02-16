<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Modify fields of elements inside container',
    'description' => 'Manipulate content elements which are children inside a EXT:container container',
    'category' => 'be',
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'state' => 'beta',
    'clearCacheOnLoad' => true,
    'version' => '1.0.1',
    'constraints' => [
        'depends' => [
            'depends' => [
                'typo3' => '9.5.0-11.99.99',
                'container' => '1.4.0-1.99.99',
            ],
        ],
    ],
];
