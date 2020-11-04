<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'blogProject') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
      .ck-editor__editable_inline {
          min-height: 200px;
      }

    .text-large {
      font-size: 150%;
      }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>

</head>
<body>
    <div id="app">
      @include('inc.navbar')
        <main class="py-4">
          <div class="container">
            @include('sweetalert::alert')
            @yield('content')
          </div>
        </main>
    </div>

    <script>
        ClassicEditor
            .create( document.querySelector( '#body' ),{
              removePlugins: [ 'Image']
          } )
            .catch( error => {
                console.error( error );
            } );
    </script>

</body>
</html>
