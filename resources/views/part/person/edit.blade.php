<?php
/**
 * @var object $person
 * @var \Collection<int, string> $genders
 * @var \Collection<int, string> $parentRoles
 * @var \Collection<int, \App\Models\Person\ParentAviable>|null $parentsAviable
 * @var \Collection<int, string> $marriageRoles
 * @var \Collection<int, \App\Models\Person\MarriageAviable>|null $marriagesAviable
 */
?>
<div class="dashboard-list">
    @include("part.person.dashboard.create")
    @include("part.person.dashboard.update")
    @include("part.person.dashboard.delete", ["person" => $person->id])
</div>
@include("part.person.person", [
    "actionForm" => route('person.update', ["person" => $person->id]),
    "isMethodFormPut" => true,
    "typeForm" => "person-update",
    "person" => $person,
    "genders" => $genders,
    "parentRoles" => $parentRoles,
    "parentsAviable" => $parentsAviable,
    "marriageRoles" => $marriageRoles,
    "marriagesAviable" => $marriagesAviable
])