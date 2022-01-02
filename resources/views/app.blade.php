{{-- Modelo raiz que será carregado na primeira visita de página. 
Isso será usado para carregar os ativos do seu site (CSS e JavaScript), 
e também conterá uma raiz para inicializar seu aplicativo JavaScript.<div> --}}
<!DOCTYPE html>
<html lang="pt" class="h-full bg-gray-200">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ asset('/js/app.js') }}" defer></script>
    @routes
  </head>
  <body>
    @inertia
  </body>
</html>
