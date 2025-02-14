@props([
    'widget' => false
])


<x-wirechat::open-modal
        component="wirechat.new-chat"
        :widget="$widget"
        >
{{$slot}}
</x-wirechat::open-modal>
