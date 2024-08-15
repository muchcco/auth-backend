<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Registro</title>
</head>
<body>
    <h1>Hola, {{ $nombres_dat }}</h1>
    <p>Se remite accesos para el aplicativo de autenticacion del centro MAC - SSCS.</p>

    <br />

    <ul>
        <li>Usuario: {{ $usuario }}</li>
        <li>Contraseña: {{ $password }}</li>
    </ul>

    <p>Nota: Si no logra ingresar con las credenciales, comunicarse con su personal TIC</p>
</body>
</html>
