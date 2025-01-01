<?php
/**
 * @var object $person
 */
?>
@if (empty($person->surname))
    {{ __("person.person_short.surname_unknown") }}
@else
    {{ $person->surname }}
@endif
{{ " " }}
@isset($person->oldSurname)
    {{ "(" }}
    @for ($i = 0; $i < count($person->oldSurname); $i++)
        {{ $person->oldSurname[$i] }}
        @if ($i + 1 !== count($person->oldSurname))
            {{ ", " }}
        @endif
    @endfor
    {{ ") " }}
@endisset
@if (empty($person->name))
    {{ __("person.person_short.name_unknown") }}
@else
    {{ $person->name }}
@endif
{{ " " }}
@if (empty($person->patronymic))
    {{ __("person.person_short.patronymic_unknown") }}
@else
    {{ $person->patronymic }}
@endif