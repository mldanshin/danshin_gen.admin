<?php
/**
 * @var object|null $activity
 */
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
        <input type="text"
            name="activities[]"
            @isset($activity)
                value="{{ $activity }}"
            @endisset
            maxlength="255"
            required
        >
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete activities person" width="25" height="25">
    </button>
</div>