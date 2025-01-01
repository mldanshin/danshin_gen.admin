<?php
/**
 * @var string $tempId
 * @var \App\Models\Person\ParentAviable $parentAviable
 */
?>
<div>
    {{ __("person.parents.person") }}
</div>
<select name="parents[{{ $tempId }}][person]"
    required
>
    <option value="" disabled selected>&nbsp;</option>
    @foreach ($parentAviable->aviablePerson as $item)
        <option value="{{ $item->id }}"
            @if ($item->id === $parentAviable->parentId)
            {{ "selected" }}
            @endif
        >
            @include("part.person.person-short", ["person" => $item])
        </option>
    @endforeach
</select>