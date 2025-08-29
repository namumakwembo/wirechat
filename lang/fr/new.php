<?php

return [
    // new-chat component
    'chat' => [
        'labels' => [
            'heading' => 'Nouvelle discussion',
            'you' => 'Vous',
        ],

        'inputs' => [
            'search' => [
                'label' => 'Rechercher des discussions',
                'placeholder' => 'Rechercher',
            ],
        ],

        'actions' => [
            'new_group' => [
                'label' => 'Nouveau groupe',
            ],
        ],

        'messages' => [
            'empty_search_result' => 'Aucun utilisateur trouvé correspondant à votre recherche.',
        ],
    ],

    // new-group component
    'group' => [
        'labels' => [
            'heading' => 'Nouveau groupe', // Assuming 'New Chat' here was a copy-paste error and should be 'New Group'
            'add_members' => 'Ajouter des membres',
        ],

        'inputs' => [
            'name' => [
                'label' => 'Nom du groupe',
                'placeholder' => 'Entrer le nom',
            ],
            'description' => [
                'label' => 'Description',
                'placeholder' => 'Facultatif',
            ],
            'search' => [
                'label' => 'Rechercher',
                'placeholder' => 'Rechercher',
            ],
            'photo' => [
                'label' => 'Photo',
            ],
        ],

        'actions' => [
            'cancel' => [
                'label' => 'Annuler',
            ],
            'next' => [
                'label' => 'Suivant',
            ],
            'create' => [
                'label' => 'Créer',
            ],
        ],

        'messages' => [
            'members_limit_error' => 'Le nombre de membres ne peut pas dépasser :count',
            'empty_search_result' => 'Aucun utilisateur trouvé correspondant à votre recherche.',
        ],
    ],
];
