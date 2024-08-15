<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmacionUsuario extends Mailable
{
    use Queueable, SerializesModels;

    
    public $nombres_dat;
    public $usuario;
    public $password;


    public function __construct($nombres_dat, $usuario, $password)
    {
        $this->nombres_dat = $nombres_dat;
        $this->usuario = $usuario;
        $this->password = $password;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmacion Usuario',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registro',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
