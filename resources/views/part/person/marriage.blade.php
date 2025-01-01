<?php
/**
 * @var \Collection<int, string> $roles
 * @var \App\Models\Person\MarriageAviable|null $marriageAviable
 */

$tempId = uniqid();
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
        <div>
            {{ __("person.marriages.role") }}
        </div>
        <select name="marriages[{{ $tempId }}][role]"
            data-temp-id="{{ $tempId }}"
            data-type="person-marriages-role"
            required
        >
            <option value="" disabled selected>&nbsp;</option>
            @foreach ($roles as $key => $value)
                <option value="{{ $key }}"
                    @if ($marriageAviable !== null && $key === $marriageAviable->roleId)
                    {{ "selected" }}
                    @endif
                >
                    {{ __("person.marriages.items.$key") }}
                </option>
            @endforeach
        </select>
        <div class="person-card-added-item-content"
            id="{{ 'person-marriage-aviable-container-' . $tempId }}">
            @if(empty($marriageAviable))
                {{ __("person.marriages.info_not_role") }}
            @else
                @include("part.person.marriage-aviable", [
                    "tempId" => $tempId,
                    "marriageAviable" => $marriageAviable
                ])
            @endif
        </div>
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete marriage person" width="25" height="25">
    </button>
</div>