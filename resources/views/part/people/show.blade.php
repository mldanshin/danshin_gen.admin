<?php
    /**
     * @var \Illuminate\Support\Collection<\App\Models\People\Person> $people
     */
?>
@if (count($people) > 0)
<ul>
    @foreach ($people as $person)
    <li class="people-person-links">
        <a href="{{ route('person.edit', ['person' => $person->id]) }}"
            data-person-id="{{ $person->id }}"
            data-type="person-edit"
        >
            {{ ($person->surname !== null) ? $person->surname : __('people.person.surname_unknown') }}
            {{ " " }}
            @if (count($person->oldSurname))
                {{ "(" }}
                @for ($i = 0; $i < count($person->oldSurname); $i++)
                    {{ $person->oldSurname[$i] }}
                    @if ($i+1 !== count($person->oldSurname))
                        {{ ", " }}
                    @endif
                @endfor
                {{ ") " }}
            @endif
            {{ ($person->name !== null) ? $person->name :__('people.person.name_unknown') }}
            {{ " " }}
            {{ ($person->patronymic !== null) ? $person->patronymic : __('people.person.patronymic_unknown') }}
        </a>
    </li>
    @endforeach
</ul>
@else
    <div>{{ __("people.not_found") }}</div>
@endif