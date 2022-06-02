<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
    <meta name="robots" content="noindex, nofollow">
    <meta name=description" content="Knock knock - Who's there? Is someone knocking?">
    @unless(app()->runningUnitTests())
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>
    @inertiaHead
    @endunless
  </head>
  <body>
    @inertia
  </body>
</html>
