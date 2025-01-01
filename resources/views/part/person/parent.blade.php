<?php
/**
 * @var \Collection<int, string> $roles
 * @var \App\Models\Person\ParentAviable|null $parentAviable
 */

$tempId = uniqid();
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
        <div>
            {{ __("person.parents.role") }}
        </div>
        <select name="parents[{{ $tempId }}][role]"
            data-temp-id="{{ $tempId }}"
            data-type="person-parents-role"
            required
        >
            <option value="" disabled selected>&nbsp;</option>
            @foreach ($roles as $key => $value)
                <option value="{{ $key }}"
                    @if ($parentAviable !== null && $key === $parentAviable->roleParentId)
                    {{ "selected" }}
                    @endif
                >
                    {{ __("person.parents.items.$key") }}
                </option>
            @endforeach
        </select>
        <div id="{{ 'person-parent-aviable-container-' . $tempId }}">
            @if(empty($parentAviable))
                {{ __("person.parents.info") }}
            @else
                @include("part.person.parent-aviable", [
                    "tempId" => $tempId,
                    "parentAviable" => $parentAviable
                ])
            @endif
        </div>
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete parent person" width="25" height="25">
    </button>
</div>