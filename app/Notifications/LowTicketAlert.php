<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tarif;

class LowTicketAlert extends Notification implements ShouldQueue
{
    use Queueable;

    private $tarif;
    private $nombreRestant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Tarif $tarif, int $nombreRestant)
    {
        $this->tarif = $tarif;
        $this->nombreRestant = $nombreRestant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->subject('🚨 Alerte: Stock de tickets WiFi faible')
                    ->bcc(config('mail.from.address')) // Envoie une copie cachée à info.wilink.ticket@gmail.com
                    ->greeting('Bonjour ' . $notifiable->nom . ',')
                    ->line('Ceci est une alerte automatique concernant votre stock de tickets sur la plateforme Faso-Wifi.')
                    ->line('Il ne vous reste actuellement que **' . $this->nombreRestant . ' tickets** disponibles pour le tarif :')
                    ->line('**' . ($this->tarif->wifi ? $this->tarif->wifi->nom : 'Wifi Inconnu') . ' - ' . $this->tarif->forfait . '**')
                    ->action('Recharger mon stock', url('/admin/tickets/create'))
                    ->line('Nous vous conseillons d\'ajouter de nouveaux tickets rapidement pour ne pas manquer de ventes !');
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
            //
        ];
    }
}
