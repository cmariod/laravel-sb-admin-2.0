<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
  <meta charset="utf-8"/>
  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="" name="description"/>
  <meta content="" name="author"/>
  
  <!-- Styles -->
  <link rel="stylesheet" href="{{ mixBaseUrl("assets/stylesheets/styles.css") }}" />
</head>
<body>
  @yield('body')
  <script type="text/javascript" src="{{ mixBaseUrl("assets/scripts/frontend.js") }}"></script>
</body>
</html>
