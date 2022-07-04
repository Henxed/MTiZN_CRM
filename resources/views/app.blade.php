<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="msapplication-TileColor" content="#f9ebff">
        <meta name="theme-color" content="#ffffff">

        <title inertia>{{ config('app.name', 'CRM') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased bg-slate-200 dark:bg-slate-900 page">
    <svg version="1.1" class="absolute -z-10 hidden dark:block" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 1440 715" style="enable-background:new 0 0 1440 715;" xml:space="preserve">
        <linearGradient id="grad" gradientUnits="userSpaceOnUse" x1="482.9734" y1="919.9106" x2="725.6234" y2="345.1806" gradientTransform="matrix(1 0 0 -1 0 716)">
            <stop  offset="0" style="stop-color: rgb(39 54 86 / 78%)"/>
            <stop  offset="1" style="stop-color:#0F1725;stop-opacity:0"/>
        </linearGradient>
        <path fill="url(#grad)" d="M0,0h1440v715H0V0z"/>
    </svg>
        @inertia

        {{-- @env ('local')
            <script src="http://a635-145-255-21-216.ngrok.io/browser-sync/browser-sync-client.js"></script>
        @endenv --}}
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(88894764, "init", {
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true,
                webvisor:true
            });
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/88894764" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
    </body>
</html>
