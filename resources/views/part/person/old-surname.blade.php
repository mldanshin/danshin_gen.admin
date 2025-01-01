<?php
/**
 * @var object|null $oldSurname
 */

$id = uniqid();
?>
<div>
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
        minlength="1"
        required
    >
    <button type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete old surname person" width="25px" height="25px">
    </button>
</div>