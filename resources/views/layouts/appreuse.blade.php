<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <title>{{ config( 'app.name' , 'BlogProject' ) }}</title>
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
    @include('inc.navbar')
    <br>
    <div class="container">
      @include('inc.messages')
      @yield('content')
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
