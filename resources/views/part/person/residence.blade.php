<?php
/**
 * @var object|null $residence
 */

$id = uniqid();
?>
<div>
    <label for="{{ 'person-residence-name' . $id }}">
        {{ __("person.residences.name") }}
    </label>
    <input id="{{ 'person-residence-name' . $id }}" 
        type="text"
        name="residences[{{ $id }}][name]"
        @isset($residence->name)
            value="{{ $residence->name }}"
        @endisset
        maxlength="255"
        required
    >
    <label for="{{ 'person-residence-date' . $id }}">
        {{ __("person.residence.date") }}
    </label>
    <input id="{{ 'person-residence-date' . $id }}" 
        type="text"
        name="residences[{{ $id }}][date]"
        @isset($residence->date)
            value="{{ $residence->date->string }}"
        @endisset
        maxlength="10"
        pattern="[0-9\?]{4}-([0\?]{1}[1-9\?]{1}|[1\?]{1}[012\?]{1})-([0-2\?]{1}[0-9\?]{1}|[3\?]{1}[01\?]{1})"
    >
    <small>
        {{ __("person.residence.date_rule") }}
    </small>
    <button type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete residence person" width="25px" height="25px">
    </button>
</div>