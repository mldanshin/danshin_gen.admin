<?php
/**
 * @var int $person
 */
?>
<button type="button" data-type="person-delete">
    <img src="{{ asset('img/person/delete.svg') }}"
        alt="person delete"
        width="56"
        height="56"
        data-type="person-delete"
        data-person-id="{{ $person }}"
        >
</button>