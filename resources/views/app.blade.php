<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Knock knock - Who's there? Is someone knocking?">
    @vite(['resources/css/app.css', 'resources/scripts/main.ts'])
    @inertiaHead
  </head>
  <body>
    @inertia
  </body>
</html>
