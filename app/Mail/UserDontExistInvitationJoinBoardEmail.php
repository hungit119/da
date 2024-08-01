<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserDontExistInvitationJoinBoardEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $senderName;
    private $senderEmail;
    private $boardName;
    private $boardID;
    private $boardInviteID;

    private $emailReceiver;
    private $roleID;
    /**
     * Create a new message instance.
     */
    public function __construct(
        $senderName,
        $senderEmail,
        $boardName,
        $boardID,
        $boardInviteID,
        $emailReceiver,
        $roleID
    )
    {
        $this->senderName    = $senderName;
        $this->senderEmail   = $senderEmail;
        $this->boardName     = $boardName;
        $this->boardID       = $boardID;
        $this->boardInviteID  = $boardInviteID;
        $this->emailReceiver = $emailReceiver;
        $this->roleID           = $roleID;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->senderEmail,
            to: $this->emailReceiver,
            subject: $this->senderName . " Invite you to join Treelo board: {$this->boardName}!"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail_guest',
            with: [
                'senderName'    => $this->senderName,
                'senderEmail'   => $this->senderEmail,
                'boardName'     => $this->boardName,
                'emailReceiver' => $this->emailReceiver,
                'boardID'       => $this->boardID,
                'boardInviteID'  => $this->boardInviteID,
                'roleID'         => $this->roleID
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
        return [];
    }
}
