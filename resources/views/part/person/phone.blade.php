<?php
/**
 * @var object|null $phone
 */
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
        <div class="person-card-rule-container">
            <input class="input-phone"
                type="tel"
                name="phones[]"
                @isset($phone)
                    value="{{ $phone }}"
                @endisset
                maxlength="12"
                pattern="^((8|\+7)[\- ]?)?(\d{3}[\- ]?)?[\d\- ]{7,10}$"
                required
            >
            <small>
                {{ __("person.phones.rule") }}
            </small>
        </div>
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete phone person" width="25" height="25">
    </button>
</div>