<?php
/**
 * @var object|null $parent
 * @var \Collection<int, string> $roles
 * @var \Collection<int, object>|null $parentsAviable
 */

$id = uniqid();
?>
<div>
    <label for="{{ 'person-parent-role' . $id }}">
        {{ __("person.parents.role") }}
    </label>
    <select name="parents[{{ $id }}][role]"
        id="{{ 'person-parent-role' . $id }}"
        required
    >
        @foreach ($roles as $key => $value)
            <option value="{{ $key }}"
                @if ($parent !== null && $key === $parent->role)
                {{ "selected" }}
                @endif
            >
                {{ __("person.parents.items.$key") }}
            </option>
        @endforeach
    </select>
    <div id="person-parent-aviable-container">
        @if(empty($parentsAviable))
            {{ __("person.parents.info") }}
        @else
            @include("part.person.parent-aviable")
        @endif
    </div>
    <button type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete parent person" width="25px" height="25px">
    </button>
</div>