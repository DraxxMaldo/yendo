<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña | Yendo</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f5f7;">

<!-- Contenedor Principal -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f5f7; padding: 40px 20px;">
    <tr>
        <td align="center">

            <!-- Tarjeta del Correo con color Vanilla Cream -->
            <table width="100%" max-width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #eeebda; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">

                <!-- Encabezado con Midnight Indigo -->
                <tr>
                    <td align="center" style="background-color: #282b4a; padding: 30px 20px;">
                        <!-- Usamos asset(), pero en producción asegúrate de que el logo esté en una URL pública -->
                        <img src="https://drive.google.com/uc?export=view&id=1ydCoEuGTRnQM5qiKiKsGeY1AkE35powM" alt="Logo Yendo" style="max-height: 40px; display: block; border: 0;">

                    </td>
                </tr>

                <!-- Cuerpo del Mensaje -->
                <tr>
                    <td style="padding: 40px 30px;">
                        <h2 style="margin-top: 0; color: #282b4a; font-size: 24px; font-weight: bold; text-align: center;">Recuperación de Acceso</h2>

                        <p style="font-size: 16px; line-height: 1.6; color: #282b4a; margin-bottom: 20px;">
                            Hola,<br><br>
                            Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Yendo</strong>. Se ha generado una nueva contraseña temporal de forma segura:
                        </p>

                        <!-- Caja de Contraseña (Fondo Indigo, Texto Cream) -->
                        <div style="text-align: center; margin: 35px 0;">
                                <span style="display: inline-block; background-color: #282b4a; color: #eeebda; font-size: 24px; font-weight: bold; padding: 15px 35px; border-radius: 8px; letter-spacing: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                                    {{ $nuevaContrasena }}
                                </span>
                        </div>

                        <p style="font-size: 16px; line-height: 1.6; color: #282b4a;">
                            Por motivos de seguridad, te recomendamos encarecidamente que <strong>inicies sesión y cambies esta contraseña</strong> desde tu perfil lo antes posible.
                        </p>

                        <!-- Botón de Acción -->
                        <div style="text-align: center; margin: 35px 0 10px;">
                            <a href="{{ route('login') }}" style="background-color: #282b4a; color: #eeebda; text-decoration: none; padding: 14px 30px; font-size: 16px; font-weight: bold; border-radius: 8px; display: inline-block;">
                                Ir al Inicio de Sesión
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Pie de página -->
                <tr>
                    <td style="background-color: #e5e2d1; border-top: 1px solid #d8d5c4; padding: 20px 30px; text-align: center;">
                        <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.5;">
                            Si no solicitaste este cambio, por favor ignora este correo o contacta a soporte.<br>
                            &copy; {{ date('Y') }} Yendo. Todos los derechos reservados.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
