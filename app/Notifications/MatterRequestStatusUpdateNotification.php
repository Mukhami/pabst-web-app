<?php

namespace App\Notifications;

use App\Models\MatterRequestApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MatterRequestStatusUpdateNotification extends Notification
{
    use Queueable;

    protected MatterRequestApproval $matterRequestApproval;

    /**
     * Create a new notification instance.
     */
    public function __construct(MatterRequestApproval $matterRequestApproval)
    {
        $this->matterRequestApproval = $matterRequestApproval;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Matter Request Approval Assignment: Client Matter Number -' . $this->matterRequestApproval->matter_request->ppg_client_matter_no)
            ->line('You have been requested to approve the following assigned Matter Request.')
            ->line('Matter PPG REF: ' . $this->matterRequestApproval->matter_request->ppg_ref)
            ->line('Title of Invention: ' . $this->matterRequestApproval->matter_request->title_of_invention)
            ->line('Client Name: ' . $this->matterRequestApproval->matter_request->client_name)
            ->action('View Matter Request', route('matter-requests.show', $this->matterRequestApproval->matter_request->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
