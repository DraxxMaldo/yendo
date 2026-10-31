<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaContrasenaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nuevaContrasena; // Variable pública para pasarla a la vista

    public function __construct($nuevaContrasena)
    {
        $this->nuevaContrasena = $nuevaContrasena;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperación de Contraseña - Yendo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Correos.nueva-contrasenha', // vista creada con el diseño del email
        );
    }
}
