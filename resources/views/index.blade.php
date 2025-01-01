<?php
    /**
     * @var \Illuminate\Support\Collection<\App\Models\People\Person> $people
     * @var \Illuminate\Support\Collection<string, string> $orders
     * @var \App\Models\People\Request $peopleFilterOrderRequest
     */
?>
@extends("layout", [
    "people" => $people,
    "orders" => $orders,
    "peopleFilterOrderRequest" => $peopleFilterOrderRequest
])

@section('main')
    @include("part.index")
@endsection