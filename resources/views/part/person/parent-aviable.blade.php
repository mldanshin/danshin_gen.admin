<?php
/**
 * @var object|null $parent
 * @var \Collection<int, object> $parentsAviable
 */

$id = uniqid();
?>
<label for="{{ 'person-parent-person' . $id }}">
    {{ __("person.parents.person") }}
</label>
<select name="parents[{{ $id }}][person]"
    id="{{ 'person-parent-person' . $id }}"
    required
>
    @foreach ($parentsAviable as $item)
        <option value="{{ $item->id }}"
            @if ($parent !== null && $item->id === $parent->person)
            {{ "selected" }}
            @endif
        >
            @include("part.person.person-short", ["person" => $item])
        </option>
    @endforeach
</select>