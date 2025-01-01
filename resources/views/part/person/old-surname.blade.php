<?php
/**
 * @var object|null $oldSurname
 */

$id = uniqid();
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
        <label for="{{ 'person-old-surname-surname' . $id }}">
            {{ __("person.old_surname.name") }}
        </label>
        <input id="{{ 'person-old-surname-surname' . $id }}" 
            type="text"
            name="old_surname[{{ $id }}][surname]"
            @isset($oldSurname->surname)
                value="{{ $oldSurname->surname }}"
            @endisset
            maxlength="255"
            required
        >
        <label for="{{ 'person-old-surname-order' . $id }}">
            {{ __("person.old_surname.order") }}
        </label>
        <input id="{{ 'person-old-surname-order' . $id }}" 
            type="number"
            name="old_surname[{{ $id }}][order]"
            @isset($oldSurname->order)
                value="{{ $oldSurname->order }}"
            @endisset
            min="1"
            required
        >
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete old surname person" width="25" height="25">
    </button>
</div>