<?php
/**
 * @var object|null $photo
 */
$id = uniqid();
?>
<div class="person-card-added-item-container">
    <div class="person-card-added-item-content">
        <label for="{{ 'person-photo-order' . $id }}">
            {{ __("person.photo.order") }}
        </label>
        <input class="input-photo-order"
            type="number"
            name="photo[{{ $id }}][order]"
            id="{{ 'person-photo-order' . $id }}"
            @isset($photo->order)
                value="{{ $photo->order }}"
            @endisset
            min="1"
            required
        >
        <label for="{{ 'person-photo-date' . $id }}">
            {{ __("person.photo.date") }}
        </label>
        <div class="person-card-rule-container">
            <input class="input-date"
                id="{{ 'person-photo-date' . $id }}" 
                type="text"
                name="photo[{{ $id }}][date]"
                @isset($photo->date)
                    value="{{ $photo->date->string }}"
                @endisset
                maxlength="10"
                pattern="[0-9\?]{4}-([0\?]{1}[1-9\?]{1}|[1\?]{1}[012\?]{1})-([0-2\?]{1}[0-9\?]{1}|[3\?]{1}[01\?]{1})"
            >
            <small>
                {{ __("person.photo.date_rule") }}
            </small>
        </div>
        <div id="{{ 'person-photo-file-img-container' . $id }}"></div>
        <div class="person-card-rule-container">
            <label class="button" for="{{ 'person-photo-file' . $id }}" title="{{ __('person.photo.file') }}">
                <img src="{{ asset('img/person/upload-photo.svg') }}"
                    alt="{{ __('person.photo.file') }}"
                    width="35"
                    height="35"
                    >
            </label>
            <input type="hidden"
                class="person-photo-file-name"
                name="photo[{{ $id }}][file_name]"
                id="{{ 'person-photo-file-name' . $id }}"
                data-type="person-photo-file-name"
                data-id="{{ $id }}"
                @isset($photo->order)
                    value="{{ $photo->fileName }}"
                @endisset
            >
            <input type="file"
                name="photo[{{ $id }}][file]"
                id="{{ 'person-photo-file' . $id }}"
                accept=".webp"
                data-id="{{ $id }}"
                data-type="person-photo-file-button"
                style="display:none"
            >
            <small>{{ __("person.photo.file_rule") }}</small>
        </div>
    </div>
    <button class="person-delete-item" type="button">
        <img src="{{ asset('img/person/delete-item.svg') }}" alt="delete photo person" width="25" height="25">
    </button>
</div>