<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuesta de Solicitud | Yendo</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #F7F5E9; color: #282B4A; padding: 20px;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 6px; border: 2px solid #282B4A;">
    <h2 style="color: #282B4A; text-transform: uppercase;">Estado de tu Solicitud de Afiliación</h2>

    <p>Hola,</p>

    @if($estado === 'aprobada')
        <p>¡Nos alegra informarte que tu solicitud de afiliación a <strong>Yendo</strong> ha sido <strong>APROBADA</strong>!</p>
        <p>Ahora cuentas con permisos de <strong>Afiliado</strong>, lo que te permitirá gestionar tu negocio y disfrutar de todas las ventajas dentro de la plataforma.</p>
        <p>Inicia sesión en tu cuenta para ver los cambios reflejados.</p>
    @else
        <p>Lamentamos informarte que tu solicitud de afiliación ha sido <strong>RECHAZADA</strong> por el equipo administrativo.</p>
        @if(!empty($motivoRechazo))
            <p><strong>Motivo:</strong> {{ $motivoRechazo }}</p>
        @endif
        <p>Puedes actualizar tu información en tu perfil e intentarlo nuevamente cuando gustes.</p>
    @endif

    <p style="margin-top: 30px;">Atentamente,<br><strong>Equipo de Soporte Yendo</strong></p>
</div>
</body>
</html>
