@props([
    'widget' => false
])


<x-wirechat::open-modal
        component="wirechat.new-group"
        :widget="$widget"
        >
{{$slot}}
</x-wirechat::open-modal>
