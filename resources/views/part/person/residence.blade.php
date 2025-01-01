<?php
/**
 * @var object|null $residence
 */

$id = uniqid();
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
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
            {{ __("person.residences.date") }}
        </label>
        <div class="person-card-rule-container">
            <input class="input-date"
                id="{{ 'person-residence-date' . $id }}" 
                type="text"
                name="residences[{{ $id }}][date]"
                @isset($residence->date)
                    value="{{ $residence->date->string }}"
                @endisset
                maxlength="10"
                pattern="[0-9\?]{4}-([0\?]{1}[1-9\?]{1}|[1\?]{1}[012\?]{1})-([0-2\?]{1}[0-9\?]{1}|[3\?]{1}[01\?]{1})"
            >
            <small>
                {{ __("person.residences.date_rule") }}
            </small>
        </div>
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete residence person" width="25" height="25">
    </button>
</div>