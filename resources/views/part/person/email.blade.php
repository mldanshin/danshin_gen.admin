<?php
/**
 * @var object|null $email
 */
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
        <input type="email"
            name="emails[]"
            @isset($email)
                value="{{ $email }}"
            @endisset
            maxlength="255"
            required
        >
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete email person" width="25" height="25">
    </button>
</div>