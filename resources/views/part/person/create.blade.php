<?php
/**
 * @var object $person
 * @var \Collection<int, string> $genders
 * @var \Collection<int, string> $parentRoles
 * @var \Collection<int, object> $parentsAviable
 */
?>
<div>
    @include("part.person.dashboard.create")
    @include("part.person.dashboard.store")
</div>
<div>
    <form action="{{ route('person.store') }}" id="person-create-form" method="POST">
        @csrf
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
        <div>
            @isset($person->oldSurname)
                @foreach ($person->oldSurname as $item)
                    @include("part.person.old-surname", ["oldSurname" => $item])
                @endforeach
            @endisset
            <button type="button">
                <img src="{{ asset('img/person/add.svg') }}" alt="add old surname person" width="25px" height="25px">
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
                {{ "disabled " }}
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
        <div>
            <input id="person-birth-date" 
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
        <input id="person-birth-place" 
            type="text"
            name="birth_place"
            @isset($person->birthPlace)
                value="{{ $person->birthPlace }}"
            @endisset
            maxlength="255"
        >
        </input>
        <label for="person-death-date">
            {{ __("person.death_date.label") }}
        </label>
        <div>
            <input id="person-death-date" 
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
        <input id="person-burial-place" 
            type="text"
            name="burial_place"
            @isset($person->burialPlace)
                value="{{ $person->burialPlace }}"
            @endisset
            maxlength="255"
        >
        </input>
        <label for="person-note">
            {{ __("person.note.label") }}
        </label>
        <textarea id="person-note" name="note">@isset($person->note){{ $person->note }}@endisset</textarea>
        <div>
            {{ __("person.activities.label") }}
        </div>
        <div>
            @isset($person->activities)
                @foreach ($person->activities as $item)
                    @include("part.person.activity", ["activity" => $item])
                @endforeach
            @endisset
            <button type="button">
                <img src="{{ asset('img/person/add.svg') }}" alt="add activity person" width="25px" height="25px">
            </button>
        </div>
        <div>
            {{ __("person.emails.label") }}
        </div>
        <div>
            @isset($person->emails)
                @foreach ($person->emails as $item)
                    @include("part.person.email", ["email" => $item])
                @endforeach
            @endisset
            <button type="button">
                <img src="{{ asset('img/person/add.svg') }}" alt="add email person" width="25px" height="25px">
            </button>
        </div>
        <div>
            {{ __("person.internet.label") }}
        </div>
        <div>
            @isset($person->internet)
                @foreach ($person->internet as $item)
                    @include("part.person.internet", ["internet" => $item])
                @endforeach
            @endisset
            <button type="button">
                <img src="{{ asset('img/person/add.svg') }}" alt="add internet person" width="25px" height="25px">
            </button>
        </div>
        <div>
            {{ __("person.phones.label") }}
        </div>
        <div>
            @isset($person->phones)
                @foreach ($person->phones as $item)
                    @include("part.person.phone", ["phone" => $item])
                @endforeach
            @endisset
            <button type="button">
                <img src="{{ asset('img/person/add.svg') }}" alt="add phone person" width="25px" height="25px">
            </button>
        </div>
        <div>
            {{ __("person.residences.label") }}
        </div>
        <div>
            @isset($person->residences)
                @foreach ($person->residences as $item)
                    @include("part.person.residence", ["residence" => $item])
                @endforeach
            @endisset
            <button type="button">
                <img src="{{ asset('img/person/add.svg') }}" alt="add residence person" width="25px" height="25px">
            </button>
        </div>
        <div>
            {{ __("person.parents.label") }}
        </div>
        <div>
            @isset($person->parents)
                @foreach ($person->parents as $item)
                    @include("part.person.parent", [
                        "parent" => $item,
                        "roles" => $parentRoles,
                        "parentsAviable" => $parentsAviable
                    ])
                @endforeach
            @endisset
            <button type="button">
                <img src="{{ asset('img/person/add.svg') }}" alt="add parent person" width="25px" height="25px">
            </button>
        </div>
    </form>
</div>