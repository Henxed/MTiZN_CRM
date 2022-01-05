<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'CRM') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased bg-slate-200 dark:bg-slate-900">
        @inertia

        {{-- @env ('local')
            <script src="http://a635-145-255-21-216.ngrok.io/browser-sync/browser-sync-client.js"></script>
        @endenv --}}

    </body>
</html>
