<?php
/**
 * @var object|null $internet
 */

$id = uniqid();
?>
<div>
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
    <button type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete internet person" width="25px" height="25px">
    </button>
</div>