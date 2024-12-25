<?php

return [
    'name'        => 'Multi-Owner Bundle',
    'description' => 'Add a campaign action that assigns multiple owners to a contact.',
    'version'     => '1.0',
    'author'      => 'webjmDesign0000',

    'services' => [
        'events' => [
            'my_multi_owner.campaign_subscriber' => [
                'class'     => \MauticPlugin\MyMultiOwnerBundle\EventListener\CampaignSubscriber::class,
                'arguments' => [
                    'mautic.user.model.user',
                    'doctrine.orm.entity_manager',
                ],
            ],
        ],
        'other' => [
            // You can define services or helper classes if needed
        ],
    ],

    'services' => [
    'forms' => [
        'my_multi_owner_assign_multiple_owners.form' => [
            'class'     => \MauticPlugin\MyMultiOwnerBundle\Form\Type\AssignMultipleOwnersType::class,
            'arguments' => [
                'mautic.user.model.user',
            ],
        ],
    ],
    // ...
],


    'campaigns' => [
        'actions' => [
            'my_multi_owner.campaign.action.assign_multiple_owners' => [
                'description' => 'plugin.my_multi_owner.assign_multiple_owners.action',
                'eventName'   => 'my_multi_owner.campaign.action.assign_multiple_owners',
                'label'       => 'Assign Multiple Owners',
                'formType'    => 'my_multi_owner_assign_multiple_owners',
            ],
        ],
    ],
];
