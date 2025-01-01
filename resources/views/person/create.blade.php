<?php
    /**
     * @var \Illuminate\Support\Collection<\App\Models\People\Person> $people
     * @var \Illuminate\Support\Collection<string, string> $orders
     * @var \App\Models\People\Request $peopleFilterOrderRequest
     * @var object $person
     * @var \Collection<int, string> $genders
     * @var \Collection<int, string> $parentRoles
     * @var \Collection<int, \App\Models\Person\ParentAviable>|null $parentsAviable
     * @var \Collection<int, string> $marriageRoles
     * @var \Collection<int, \App\Models\Person\MarriageAviable>|null $marriagesAviable
     */
?>
@extends("layout", [
    "people" => $people,
    "orders" => $orders,
    "peopleFilterOrderRequest" => $peopleFilterOrderRequest
])

@section('main')
    @include("part.person.create")
@endsection