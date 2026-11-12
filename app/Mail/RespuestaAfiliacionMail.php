<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RespuestaAfiliacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $estado;
    public $motivoRechazo;

    public function __construct($estado, $motivoRechazo = null)
    {
        $this->estado = $estado;
        $this->motivoRechazo = $motivoRechazo;
    }

    public function envelope(): Envelope
    {
        $asunto = $this->estado === 'aprobada'
            ? '¡Tu solicitud de afiliación a Yendo ha sido Aprobada!'
            : 'Actualización sobre tu solicitud de afiliación a Yendo';

        return new Envelope(
            subject: $asunto,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Correos.respuesta-afiliacion', // Vista Blade del correo
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
