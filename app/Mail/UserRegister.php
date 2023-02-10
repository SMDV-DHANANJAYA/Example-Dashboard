<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegister extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type,$user)
    {
        $this->type = $type;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        if($this->type == "user"){
            return new Envelope(
                subject: 'User Registration',
            );
        }
        else{
            return new Envelope(
                subject: 'Admin Registration',
            );
        }
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        if($this->type == "user"){
            return new Content(
                markdown: 'emails.userRegister',
            );
        }
        else{
            return new Content(
                markdown: 'emails.adminRegister',
            );
        }
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
