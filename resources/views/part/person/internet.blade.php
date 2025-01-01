<?php
/**
 * @var object|null $internet
 */

$id = uniqid();
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
        <label for="{{ 'person-internet-name' . $id }}">
            {{ __("person.internet.name") }}
        </label>
        <input id="{{ 'person-internet-name' . $id }}" 
            type="text"
            name="internet[{{ $id }}][name]"
            @isset($internet->name)
                value="{{ $internet->name }}"
            @endisset
            maxlength="255"
            required
        >
        <label for="{{ 'person-internet-url' . $id }}">
            {{ __("person.internet.url") }}
        </label>
        <input id="{{ 'person-internet-url' . $id }}" 
            type="url"
            name="internet[{{ $id }}][url]"
            @isset($internet->url)
                value="{{ $internet->url }}"
            @endisset
            maxlength="255"
            required
        >
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete internet person" width="25" height="25">
    </button>
</div>