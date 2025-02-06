<?php

arch('app')
    ->expect('Namu\WireChat')
    ->not->toUse(['die', 'dd', 'dump']);

arch('Traits test ')
    ->expect('Namu\WireChat\Traits')
    ->toBeTraits();

arch('Make sure Actor is only used in Chatable Trait')
    ->expect('Namu\WireChat\Traits\Actor')
    ->toOnlyBeUsedIn('Namu\WireChat\Traits\Chatable');

arch('Make sure Actionable is used in Conversation Model')
    ->expect('Namu\\WireChat\\Traits\\Actionable')
    ->toBeUsedIn('Namu\WireChat\Models\Conversation');

arch('Make sure Actionable is used in Message Model')
    ->expect('Namu\\WireChat\\Traits\\Actionable')
    ->toBeUsedIn('Namu\WireChat\Models\Message');

arch('Make sure Actionable is used in Participant Model')
    ->expect('Namu\\WireChat\\Traits\\Actionable')
    ->toBeUsedIn('Namu\WireChat\Models\Participant');

describe('Test Compenents use Widget Trait', function () {
    arch('make the component use Widget Trait')
    //Chat
        ->expect('Namu\WireChat\Livewire\Chat\Chat')
        ->expect('Namu\WireChat\Livewire\Chat\Chats')
    //componnets
        ->expect('Namu\WireChat\Livewire\Components\NewChat')
        ->expect('Namu\WireChat\Livewire\Components\NewGroup')
    //Info
        ->expect('Namu\WireChat\Livewire\Info\AddMembers')
        ->expect('Namu\WireChat\Livewire\Info\Info')
        ->expect('Namu\WireChat\Livewire\Info\Members')
        ->toUseTrait('Namu\WireChat\Traits\Widget');

});
