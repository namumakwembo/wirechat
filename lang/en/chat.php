<?php

return [

    /**-------------------------
     * Chat
     *------------------------*/
    'labels' => [

        'you_replied_to_yourself' => 'You replied to Yourself',
        'participant_replied_to_you' => ':sender replied to You',
        'participant_replied_to_themself' => ':sender replied to Themself',
        'participant_replied_other_participant' => ':sender replied to :receiver',
        'you' => 'You',
        'user' => 'User',
        'replying_to' => 'Replying to :participant',
        'replying_to_yourself' => 'Replying to Yourself',
        'attachment' => 'Attachment',
        'type_a_message' => 'Type a message...',
    ],

    'message_groups' => [
        'today' => 'Today',
        'yesterday' => 'Yesterday',

    ],

    'actions' => [
        'open_group_info' => [
            'label' => 'Group Info',
        ],
        'open_chat_info' => [
            'label' => 'Chat Info',
        ],
        'close_chat' => [
            'label' => 'Close Chat'
        ],
        'clear_chat' => [
            'label' => 'Clear Chat History',
            'confirmation_message' => 'Are you sure you want to clear your chat history? This will only clear your chat and will not affect other participants.',
        ],
        'delete_chat' => [
            'label' => 'Delete Chat',
            'confirmation_message' => 'Are you sure you want to delete this chat? This will only remove the chat from your side and will not delete it for other participants.',
        ],

        'delete_for_everyone' => [
            'label' => 'Delete for everyone',
            'confirmation_message' => 'Are you sure?',
        ],
        'delete_for_me' => [
            'label' => 'Delete for me',
            'confirmation_message' => 'Are you sure?',
        ],
        'reply' => [
            'label' => 'Reply'
        ],

        'exit_group' => [
            'label' => 'Exit Group',
            'confirmation_message' => 'Are you sure you want to exit this group?',
        ],
        'upload_file' => [
            'label' => 'File'
        ],
        'upload_media' => [
            'label' => 'Photos & Videos'
        ],
    ],


    'messages' => [

        'cannot_exit_self_or_private_conversation' => 'Cannot exit self or private conversation',
        'owner_cannot_exit_conversation' => 'Owner cannot exit conversation',
        'rate_limit' => 'Too many attempts!, Please slow down',
        'conversation_not_found' => 'Conversation not found.',
        'conversation_id_required' => 'A conversation id is required',
        'invalid_conversation_input' => 'Invalid conversation input.'
    ],


    /**-------------------------
    * Info
    *------------------------*/

    'info'=>[
        'heading'=>'Chat Info',
        'actions' => [
            'delete_chat' => [
                'label' => 'Delete Chat',
                'confirmation_message' => 'Are you sure you want to delete this chat? This will only remove the chat from your side and will not delete it for other participants.',
            ]    
        ]

    ]



];
