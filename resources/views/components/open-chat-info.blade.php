@props([
    'conversation' => null, //Should be conversation  ID (Int)
    'widget' => false
])


<x-wirechat::open-chat-drawer 
        component="wirechat.chat.info"
        conversation="{{$conversation}}"
        :widget="$widget"
        >
{{$slot}}
</x-wirechat::open-chat-drawer>
