<!DOCTYPE HTML>
<html lang="{{ app()->getLocale() }}" dir="{{ $direction }}">
<head> 
  <title>{{ data_get($component, 'title') }} | {{ data_get($component, 'subtitle') }}</title>
  <meta name="title" content="{{ data_get($component, 'title') }} | {{ data_get($component, 'subtitle') }}">
  <meta name="description" content="{{ data_get($component, 'description') }}">
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:title" content="{{ data_get($component, 'title') }} | {{ data_get($component, 'subtitle') }}">
  <meta property="og:description" content="{{ data_get($component, 'description') }}">

  <!-- Bootstrap CSS --> 
  {!! 
    // renders avaialble plugins that should renders in the header
    $plugins->filterForHead()->toHtml() 
  !!}  
</head> 
<body 
  data-component="{{ data_get($component, 'uriKey') }}"  
  data-fragment="{{ data_get($component, 'fragment.uriKey') }}" 
>  
  {!! 
    // renders avaialble widgets
    $widgets->toHtml()
  !!}  
  {!! 
    // renders avaialble plugins that should render in the footer
    $plugins->filterForBody()->toHtml() 
  !!} 
</body>
</html>