<?php
/**
 * @var string $actionForm
 * @var bool $isMethodFormPut
 * @var object $typeForm
 * @var object $person
 * @var \Collection<int, string> $genders
 * @var \Collection<int, string> $parentRoles
 * @var \Collection<int, \App\Models\Person\ParentAviable>|null $parentsAviable
 * @var \Collection<int, string> $marriageRoles
 * @var \Collection<int, \App\Models\Person\MarriageAviable>|null $marriagesAviable
 */
?>
<div>
    <form class="person-card"
        action="{{ $actionForm }}"
        id="person-form"
        method="POST"
        name="person-form"
        data-type="{{ $typeForm }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if ($isMethodFormPut)
            @method('PUT')
        @endif
        @isset ($person->id)
            <input id="person-id" type="hidden" name="id" value="{{ $person->id }}">
        @endisset
        <label for="person-is-unavailable">
            {{ __("person.unavailable.label") }}
        </label>
        <input type="checkbox"
            name="is_unavailable"
            id="person-is-unavailable"
            @if ($person->isUnavailable)
                {{ "checked" }}
            @endif
        >
        <label for="person-is-live">
            {{ __("person.live.label") }}
        </label>
        <input type="checkbox"
            name="is_live"
            id="person-is-live"
            @if ($person->isLive)
                {{ "checked" }}
            @endif
        >
        <label for="person-gender">
            {{ __("person.gender.label") }}
        </label>
        <select name="gender" id="person-gender" required>
            <option value="" disabled>&nbsp;</option>
            @foreach ($genders as $key => $value)
                <option value="{{ $key }}"
                    @if ($key === $person->gender)
                    {{ "selected" }}
                    @endif
                >
                    {{ __("person.gender.items.$key") }}
                </option>
            @endforeach
        </select>
        <label for="person-surname">
            {{ __("person.surname.label") }}
        </label>
        <input id="person-surname" 
                type="text"
                name="surname"
                @isset($person->surname)
                    value="{{ $person->surname }}"
                @endisset
                maxlength="255"
        >
        </input>
        <label>
            {{ __("person.old_surname.label") }}
        </label>
        <div class="person-card-added-container">
            @isset($person->oldSurname)
                @foreach ($person->oldSurname as $item)
                    @include("part.person.old-surname", ["oldSurname" => $item])
                @endforeach
            @endisset
            <button id="person-create-old-surname" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}" alt="add old surname person" width="25" height="25">
            </button>
        </div>
        <label for="person-name">
            {{ __("person.name.label") }}
        </label>
        <input id="person-name" 
            type="text"
            name="name"
            @isset($person->name)
                value="{{ $person->name }}"
            @endisset
            maxlength="255"
        >
        </input>
        <label for="person-patronymic">
            {{ __("person.patronymic.label") }}
        </label>
        <input id="person-patronymic" 
            type="text"
            name="patronymic"
            @isset($person->patronymic)
                value="{{ $person->patronymic }}"
            @endisset
            @if (!$person->hasPatronymic)
                {{ "disabled" }}
            @endif
            maxlength="255"
        >
        <label for="person-has-patronymic">
            {{ __("person.has_patronymic.label") }}
        </label>
        <input type="checkbox"
            name="has_patronymic"
            id="person-has-patronymic"
            @if ($person->hasPatronymic)
                {{ "checked" }}
            @endif
        >
        </input>
        <label for="person-birth-date">
            {{ __("person.birth_date.label") }}
        </label>
        <div class="person-card-rule-container">
            <input class="input-date"
                id="person-birth-date" 
                type="text"
                name="birth_date"
                @isset($person->birthDate)
                    value="{{ $person->birthDate->string }}"
                @endisset
                maxlength="10"
                pattern="[0-9\?]{4}-([0\?]{1}[1-9\?]{1}|[1\?]{1}[012\?]{1})-([0-2\?]{1}[0-9\?]{1}|[3\?]{1}[01\?]{1})"
            >
            </input>
            <small>{{ __("person.birth_date.rule") }}</small>
        </div>
        <label for="person-birth-place">
            {{ __("person.birth_place.label") }}
        </label>
        <textarea id="person-birth-place" name="birth_place">@isset($person->birthPlace){{ $person->birthPlace }}@endisset</textarea>
        <label for="person-death-date">
            {{ __("person.death_date.label") }}
        </label>
        <div class="person-card-rule-container">
            <input class="input-date"
                id="person-death-date" 
                type="text"
                name="death_date"
                @isset($person->deathDate)
                    value="{{ $person->deathDate->string }}"
                @endisset
                maxlength="10"
                pattern="[0-9\?]{4}-([0\?]{1}[1-9\?]{1}|[1\?]{1}[012\?]{1})-([0-2\?]{1}[0-9\?]{1}|[3\?]{1}[01\?]{1})"
            >
            </input>
            <small>{{ __("person.death_date.rule") }}</small>
        </div>
        <label for="person-burial-place">
            {{ __("person.burial_place.label") }}
        </label>
        <textarea id="person-burial-place" name="burial_place">@isset($person->burialPlace){{ $person->burialPlace }}@endisset</textarea>
        <label for="person-note">
            {{ __("person.note.label") }}
        </label>
        <textarea id="person-note" name="note">@isset($person->note){{ $person->note }}@endisset</textarea>
        <div>
            {{ __("person.activities.label") }}
        </div>
        <div class="person-card-added-container">
            @isset($person->activities)
                @foreach ($person->activities as $item)
                    @include("part.person.activity", ["activity" => $item])
                @endforeach
            @endisset
            <button id="person-create-activity" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}"
                    alt="add activity person"
                    width="25"
                    height="25"
                    >
            </button>
        </div>
        <div>
            {{ __("person.emails.label") }}
        </div>
        <div class="person-card-added-container">
            @isset($person->emails)
                @foreach ($person->emails as $item)
                    @include("part.person.email", ["email" => $item])
                @endforeach
            @endisset
            <button id="person-create-email" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}"
                    alt="add email person"
                    width="25"
                    height="25"
                    >
            </button>
        </div>
        <div>
            {{ __("person.internet.label") }}
        </div>
        <div class="person-card-added-container">
            @isset($person->internet)
                @foreach ($person->internet as $item)
                    @include("part.person.internet", ["internet" => $item])
                @endforeach
            @endisset
            <button id="person-create-internet" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}" alt="add internet person" width="25" height="25">
            </button>
        </div>
        <div>
            {{ __("person.phones.label") }}
        </div>
        <div class="person-card-added-container">
            @isset($person->phones)
                @foreach ($person->phones as $item)
                    @include("part.person.phone", ["phone" => $item])
                @endforeach
            @endisset
            <button id="person-create-phone" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}" alt="add phone person" width="25" height="25">
            </button>
        </div>
        <div>
            {{ __("person.residences.label") }}
        </div>
        <div class="person-card-added-container">
            @isset($person->residences)
                @foreach ($person->residences as $item)
                    @include("part.person.residence", ["residence" => $item])
                @endforeach
            @endisset
            <button id="person-create-residence" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}" alt="add residence person" width="25" height="25">
            </button>
        </div>
        <div>
            {{ __("person.parents.label") }}
        </div>
        <div class="person-card-added-container" id="person-parents-container">
            @isset($parentsAviable)
                @foreach ($parentsAviable as $item)
                    @include("part.person.parent", [
                        "roles" => $parentRoles,
                        "parentAviable" => $item
                    ])
                @endforeach
            @endisset
            <button id="person-create-parent" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}" alt="add parent person" width="25" height="25">
            </button>
        </div>
        <div>
            {{ __("person.marriages.label") }}
        </div>
        <div class="person-card-added-container" id="person-marriages-container">
            @isset($marriagesAviable)
                @foreach ($marriagesAviable as $item)
                    @include("part.person.marriage", [
                        "roles" => $marriageRoles,
                        "marriageAviable" => $item
                    ])
                @endforeach
            @endisset
            <button id="person-create-marriage" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}" alt="add marriage person" width="25" height="25">
            </button>
        </div>
        <div>
            {{ __("person.photo.label") }}
        </div>
        <div class="person-card-added-container" id="person-photo-container">
            @isset($person->photo)
                @foreach ($person->photo as $item)
                    @include("part.person.photo", [
                        "photo" => $item
                    ])
                @endforeach
            @endisset
            <button id="person-create-photo" type="button">
                <img src="{{ asset('img/person/add-dark.svg') }}" alt="add photo person" width="25" height="25">
            </button>
        </div>
    </form>
</div>