@php
$config = [
    'appName' => config('app.name'),
    'locale' => $locale = app()->getLocale(),
    'locales' => config('app.locales'),
    'facebookAuth' => config('services.facebook.client_id'),
    'googleAuth' => config('services.google.client_id')
];
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Speak Forms</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href='https://use.fontawesome.com/releases/v5.0.13/css/all.css' rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">


        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">

        <!-- Custom Styling -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    </head>
    <body class="custom_scrollbar">
        <div id="audioRecMain">
        </div>
        <!--- Scripts -->
        <script>
            window.config = @json($config);
        </script>
        <script src="{{ asset('js/recorder.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
