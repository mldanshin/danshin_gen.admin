<?php
/**
 * @var object|null $email
 */
?>
<div>
    <input type="email"
        name="emails[]"
        @isset($email)
            value="{{ $email }}"
        @endisset
        maxlength="255"
        required
    >
    <button type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete email person" width="25px" height="25px">
    </button>
</div>