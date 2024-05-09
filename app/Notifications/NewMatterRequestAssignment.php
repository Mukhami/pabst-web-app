<?php

namespace App\Notifications;

use App\Models\MatterRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMatterRequestAssignment extends Notification implements ShouldQueue
{
    use Queueable;

    public MatterRequest $matterRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct($matterRequest)
    {
        $this->matterRequest = $matterRequest;
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
            ->subject('New Matter Request Assignment: Client Matter Number -' . $this->matterRequest->ppg_client_matter_no)
            ->line('You have been assigned to a new Matter Request.')
            ->line('Matter PPG REF: ' . $this->matterRequest->ppg_ref)
            ->line('Title of Invention: ' . $this->matterRequest->title_of_invention)
            ->line('Client Name: ' . $this->matterRequest->client_name)
            ->action('View Matter Request', route('matter-requests.show', $this->matterRequest->id));
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
