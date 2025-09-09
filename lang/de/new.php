<?php

return [

    // new-chat component
    'chat' => [
        'labels' => [
            'heading' => 'Neuer Chat',
            'you' => 'Sie',

        ],

        'inputs' => [
            'search' => [
                'label' => 'Konversationen suchen',
                'placeholder' => 'Suchen',
            ],
        ],

        'actions' => [
            'new_group' => [
                'label' => 'Neue Gruppe',
            ],

        ],

        'messages' => [

            'empty_search_result' => 'Keine Benutzer gefunden, die Ihrer Suche entsprechen.',
        ],
    ],

    // new-group-Komponente
    'group' => [
        'labels' => [
            'heading' => 'Neuer Chat',
            'add_members' => 'Mitglieder hinzufügen',

        ],

        'inputs' => [
            'name' => [
                'label' => 'Gruppenname',
                'placeholder' => 'Name eingeben',
            ],
            'description' => [
                'label' => 'Beschreibung',
                'placeholder' => 'Optional',
            ],
            'search' => [
                'label' => 'Suchen',
                'placeholder' => 'Suchen',
            ],
            'photo' => [
                'label' => 'Foto',
            ],
        ],

        'actions' => [
            'cancel' => [
                'label' => 'Abbrechen',
            ],
            'next' => [
                'label' => 'Weiter',
            ],
            'create' => [
                'label' => 'Erstellen',
            ],

        ],

        'messages' => [
            'members_limit_error' => 'Mitgliederanzahl darf :count nicht überschreiten',
            'empty_search_result' => 'Keine Benutzer gefunden, die Ihrer Suche entsprechen.',
        ],
    ],

];
