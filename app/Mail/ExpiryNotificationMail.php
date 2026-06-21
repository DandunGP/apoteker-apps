<?php

namespace App\Mail;

use App\Models\User;
use App\Models\MedicineBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiryNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $batch;
    public $messageContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, MedicineBatch $batch, string $messageContent)
    {
        $this->user = $user;
        $this->batch = $batch;
        $this->messageContent = $messageContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $medicineName = $this->batch->medicine->nama ?? 'Obat';
        return $this->subject('Peringatan Kadaluwarsa: ' . $medicineName)
                    ->view('emails.expiry_notification');
    }
}
