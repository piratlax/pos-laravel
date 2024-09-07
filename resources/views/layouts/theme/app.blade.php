<!doctype html>
<html lang="es" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS | Punto de Venta Laravel</title>
  <!--favicon-->
  <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png">
  @livewireStyles
  @include('layouts.theme.styles')
  <style>
    .high-contrast {
        background-color: white;
        color: black;
    }

    @media (forced-colors: active) {
        .high-contrast {
            background-color: Canvas;
            color: CanvasText;
        }
    }
</style>
  
</head>

<body>

@include('layouts.theme.head')


@include('layouts.theme.sidebar')

  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">

    
            @yield('content')

    </div>
  </main>
  <!--end main wrapper-->


   
@include('layouts.theme.footer')


@include('layouts.theme.switcher')


@include('layouts.theme.scripts')
@livewireScripts  

</body>

</html>