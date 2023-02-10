<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserPayrollReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$path)
    {
        $this->user = $user;
        $this->path = $path;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Monthly Payroll Report',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.monthlyPayrollReport',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            Attachment::fromStorage($this->path)
                ->as('payroll_report.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
