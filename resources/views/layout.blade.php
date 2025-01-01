<?php
    /**
     * @var \Illuminate\Support\Collection<int, \App\Models\People\Person> $people
     * @var \Illuminate\Support\Collection<string, string> $orders
     * @var \App\Models\People\Request $peopleFilterOrderRequest
     */
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>{{ __("layout.title") }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="body">
        <header class="header">
            <nav>
                <a href="{{ route('index') }}">
                    <img src="{{ asset('img/layout/logo.svg') }}"
                        alt="logo genealogy"
                        width="56px"
                        height="56px"
                    >
                </a>
            </nav>
        </header>
        <div class="content">
            <aside class="aside">
                <form action="">
                    <input class="filter-order-form"
                        type="search"
                        name="search"
                        id="people_search"
                        placeholder="{{ __('filter.search.placeholder') }}"
                        value="{{ ($peopleFilterOrderRequest->search !== null) ? $peopleFilterOrderRequest->search : '' }}"
                    >
                    <div class="order-container">
                        <div>{{ __('order.title') }}</div>
                        <ul>
                            <li>
                                <label for="order-name">{{ __("order.types.name.label") }}</label>
                                <input type="radio"
                                    name="order"
                                    id="order-name"
                                    value="{{ $orders['name'] }}"
                                    @if ($peopleFilterOrderRequest->order === "name")
                                        {{ "checked" }}
                                    @endif
                                >
                            </li>
                            <li>
                                <label for="order-age">{{ __("order.types.age.label") }}</label>
                                <input type="radio"
                                    name="order"
                                    id="order-age"
                                    value="{{ $orders['age'] }}"
                                    @if ($peopleFilterOrderRequest->order === "age")
                                        {{ "checked" }}
                                    @endif
                                >
                            </li>
                        </ul>
                    </div>
                </form>
                @include("part.people.show")
            </aside>
            <main class="main">
                @yield("content")
            </main>
        </div>
        <footer class="footer">
            <div>
                <span>{{ __('layout.autor.role') }} {{ __('layout.autor.name') }}</span>
                <span>2021-{{ date("Y") }}</span>
            </div>
            <address>
                <a href="{{ 'mailto:' . __('layout.autor.email') }}" rel="nofollow">{{ __('layout.autor.email') }}</a>
            </address>
        </footer>
        <div style="display: none">
            <span id="message-error-default">{{ __("message.error.default") }}</span>
            <span id="message-error-302">{{ __("message.error.302") }}</span>
            <span id="confirmation-edit">{{ __("message.confirmation.edit") }}</span>
            <span id="confirmation-deletion">{{ __("message.confirmation.deletion") }}</span>
            <span id="save-ok">{{ __("message.ok.save") }}</span>
            <span id="delete-ok">{{ __("message.ok.delete") }}</span>
        </div>
        @routes
    </body>
</html>