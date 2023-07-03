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
    private $client_phone;
    private $client_latlng;
    private $product_name;
    private $company_name;
    private $owner_name;
    private $company_coordinate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_name, $user_email, $client_phone, $client_latlng, $product_name, $company_name, $owner_name, $company_coordinate)
    {
        $this->afterCommit();
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->client_phone = $client_phone;
        $this->client_latlng = $client_latlng;
        $this->product_name = $product_name;
        $this->company_name = $company_name;
        $this->owner_name = $owner_name;
        $this->company_coordinate = $company_coordinate;
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
     * %2C == , na URL do Mapa 
     */
    public function toMail($notifiable)
    {
        $latitude_client = $this->client_latlng['latitude']; 
        $longitude_client = $this->client_latlng['longitude'];

        $latitude_company = $this->company_coordinate->latitude; 
        $longitude_company = $this->company_coordinate->longitude;

        $empty_client = ($latitude_client == 0 || $longitude_client == 0);
        $empty_company = ($latitude_client == 0 || $longitude_company == 0);

        return (new MailMessage)
                    ->subject('Encomenda - ' . $this->user_name)
                    ->greeting($this->company_name)
                    ->line($this->owner_name)
                    ->line('Encomenda: ' . $this->product_name)
                    ->line('Por: ' . $this->user_name)
                    ->line('Email: ' . $this->user_email)
                    ->line('Telefone: ' . $this->client_phone)
                    ->action($empty_client ? 'Sem lozalização no Google Maps' : 'Localização no Google Maps', ($empty_client ? '' : 'https://www.google.com/maps/dir/?api=1') . ($empty_company ? '' : '&origin=' . $latitude_company . '%2C' . $longitude_company) . '&destination=' . $latitude_client . '%2C' . $longitude_client);
                    //Mapa sem routa ->action($result ? 'Sem lozalização no Google Maps' : 'Localização no Google Maps', $result ? '' : 'https://www.google.com/maps/search/?api=1&query=' . $latitude . '%2C' . $longitude);
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
            'message' => 'encomendou',
            'product_name' => $this->product_name
        ];
    }
}
