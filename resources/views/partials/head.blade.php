<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<script src="https://unpkg.com/lucide@latest"></script>
{{-- Alpine is bundled/bootstrapped by Livewire in this app. Loading it again causes "multiple instances" warnings. --}}

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
@livewireStyles

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
