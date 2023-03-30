<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EncomendaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $user_name;
    private $user_email;
    private $product_name;
    private $company_name;
    private $owner_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_name, $user_email, $product_name, $company_name, $owner_name)
    {
        $this->afterCommit();
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->product_name = $product_name;
        $this->company_name = $company_name;
        $this->owner_name = $owner_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Encomenda - ' . $this->user_name)
                    ->greeting($this->company_name)
                    ->line($this->owner_name)
                    ->line('Encomenda: ' . $this->product_name)
                    ->line('Por: ' . $this->user_name)
                    ->line('Email: ' . $this->user_email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_name' =>  $this->user_name,
            'message' => 'encomendaou',
            'product_name' => $this->product_name
        ];
    }
}
