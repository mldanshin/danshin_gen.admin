<?php
/**
 * @var object|null $phone
 */
?>
<div>
    <input type="tel"
        name="phones[]"
        @isset($phone)
            value="{{ $phone }}"
        @endisset
        maxlength="10"
        pattern="^((8|\+7)[\- ]?)?(\d{3}[\- ]?)?[\d\- ]{7,10}$"
        required
    >
    <small>
        {{ __("person.phones.rule") }}
    </small>
    <button type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete phone person" width="25px" height="25px">
    </button>
</div>