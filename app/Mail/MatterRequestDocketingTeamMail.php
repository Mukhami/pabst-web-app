<?php

namespace App\Mail;

use App\Models\MatterRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MatterRequestDocketingTeamMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public MatterRequest $matterRequest;

    /**
     * Create a new message instance.
     */
    public function __construct($matterRequest)
    {
        $this->matterRequest = $matterRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Matter Request Approved: Client Matter Number - ' . $this->matterRequest->ppg_client_matter_no;
        $subject .= ' PPG Ref - ' . $this->matterRequest->ppg_ref;
        return new Envelope(
            from: new Address('noreply@alamaworks.com', 'Mkenga Mail Notifications'),
            subject: $subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.docketing-email-template',
            with: [
                'matterRequest' => $this->matterRequest
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.view', ['matterRequest' => $this->matterRequest]);
        $pdfContent = $pdf->output();
        $matterNo = $this->matterRequest->ppg_client_matter_no;
        $documentName = "Matter-Request-$matterNo.pdf";
        return [
            Attachment::fromData(fn () => $pdfContent, $documentName)
                ->withMime('application/pdf'),
        ];
    }
}
