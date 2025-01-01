<?php
/**
 * @var string $tempId
 * @var \App\Models\Person\MarriageAviable $marriageAviable
 */
?>
<div>
    {{ __("person.marriages.role_soulmate") }}
    <input type="hidden"
        name="marriages[{{ $tempId }}][soulmate_role]"
        value="{{ $marriageAviable->soulmateRoleId }}"
    >
</div>
<div>
    {{ __("person.marriages.items.{$marriageAviable->soulmateRoleId}") }}
</div>
<div>
    {{ __("person.marriages.person") }}
</div>
<select name="marriages[{{ $tempId }}][soulmate]"
    required
>
    <option value="" disabled selected>&nbsp;</option>
    @foreach ($marriageAviable->aviablePerson as $item)
        <option value="{{ $item->id }}"
            @if ($item->id === $marriageAviable->soulmateId)
            {{ "selected" }}
            @endif
        >
            @include("part.person.person-short", ["person" => $item])
        </option>
    @endforeach
</select>