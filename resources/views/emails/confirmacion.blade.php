<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Registro</title>
</head>
<body>
    <h1>Hola, {{ $nombres_dat }}</h1>
    <p>Tu registro ha sido completado con éxito.</p>
    @if (!empty($pending))
        <p>Pendientes en responder:</p>
        <ul>
            @foreach ($pending as $field)
                <li>{{ $field }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay pendientes.</p>
    @endif
</body>
</html>
