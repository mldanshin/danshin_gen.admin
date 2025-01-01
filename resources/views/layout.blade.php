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
    <body class="body dark-theme">
        <header class="header">
            <nav>
                <a href="{{ route('index') }}">
                    <img src="{{ asset('img/layout/logo.svg') }}"
                        alt="logo genealogy"
                        width="56"
                        height="56"
                    >
                </a>
            </nav>
            <button class="people-toggle-visibility" type="button">
                <img src="{{ asset('img/people/toggle.svg') }}"
                    alt="toggle people visibility"
                    width="56"
                    height="56"
                    >
            </button>
            <button id="theme-toggle" type="button">
                <img class="theme-icon"
                    id="theme-toggle-img"
                    src="{{ asset('img/layout/moon.svg') }}"
                    alt="toggle theme"
                    width="56"
                    height="56"
                    >
            </button>
        </header>
        <div class="content" id="content">
            <aside class="aside" id="aside">
                <button class="people-toggle-visibility"
                    type="button"
                    >
                    <img class="theme-icon"
                        id="people-toggle-visibility-img"
                        src="{{ asset('img/people/close-dark.svg') }}"
                        alt="toggle theme"
                        width="25"
                        height="25"
                        >
                </button>
                <input type="checkbox"
                    name="people-state-visibility"
                    id="people-state-visibility"
                    checked
                    style="display: none"
                    >
                <form id="people-form" action="{{ route('part.people.show') }}" method="GET">
                    <input class="filter-order-form"
                        type="search"
                        name="search"
                        id="people-search"
                        placeholder="{{ __('filter.search.placeholder') }}"
                        value="{{ ($peopleFilterOrderRequest->search !== null) ? $peopleFilterOrderRequest->search : '' }}"
                    >
                    <div class="order-container">
                        <div>{{ __('order.title') }}</div>
                        <ul class="order-list">
                            <li>
                                <input type="radio"
                                    name="order"
                                    id="order-name"
                                    value="{{ $orders['name'] }}"
                                    @if ($peopleFilterOrderRequest->order === "name")
                                        {{ "checked" }}
                                    @endif
                                >
                                <label for="order-name">{{ __("order.types.name.label") }}</label>
                            </li>
                            <li>
                                <input type="radio"
                                    name="order"
                                    id="order-age"
                                    value="{{ $orders['age'] }}"
                                    @if ($peopleFilterOrderRequest->order === "age")
                                        {{ "checked" }}
                                    @endif
                                >
                                <label for="order-age">{{ __("order.types.age.label") }}</label>
                            </li>
                        </ul>
                    </div>
                </form>
                <div class="people-list-container" id="people-list-container">
                    @include("part.people.show")
                </div>
            </aside>
            <main class="main" id="main">
                @yield("main")
            </main>
        </div>
        <footer class="footer">
            <div class="footer-author">
                <span>{{ __('layout.autor.role') }} {{ __('layout.autor.name') }}</span>
                <span>2021-{{ date("Y") }}</span>
            </div>
            <address>
                <a href="{{ 'mailto:' . __('layout.autor.email') }}" rel="nofollow">{{ __('layout.autor.email') }}</a>
            </address>
        </footer>
        @include("part.toast")
        @include("part.spinner")
        <div style="display: none">
            <span id="message-error-default">{{ __("message.error.default") }}</span>
            <span id="message-error-302">{{ __("message.error.302") }}</span>
            <span id="message-error-422">{{ __("message.error.422") }}</span>
            <span id="confirmation-edit">{{ __("message.confirmation.edit") }}</span>
            <span id="confirmation-deletion">{{ __("message.confirmation.deletion") }}</span>
            <span id="save-ok">{{ __("message.ok.save") }}</span>
            <span id="delete-ok">{{ __("message.ok.delete") }}</span>
        </div>
        @routes
    </body>
</html>