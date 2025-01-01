<?php
/**
 * @var object|null $activity
 */
?>
<div>
    <input type="text"
        name="activities[]"
        @isset($activity)
            value="{{ $activity }}"
        @endisset
        maxlength="255"
        required
    >
    <button type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete activities person" width="25px" height="25px">
    </button>
</div>