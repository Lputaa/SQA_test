<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation; // Import model Reservation

class ReservationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Buat instance pesan baru.
     */
    public function __construct(
        public Reservation $reservation // Secara otomatis menerima & menyimpan objek reservasi
    ) {}

    /**
     * Dapatkan amplop pesan.
     */
public function envelope(): Envelope
{
    $statusText = ucfirst($this->reservation->status); // Contoh: Confirmed, Cancelled
    $restaurantName = $this->reservation->restaurant->name;
    $subject = "Status Reservasi Anda di {$restaurantName} Diperbarui menjadi {$statusText}";

    if ($this->reservation->status === 'confirmed') {
        $subject = "Reservasi Anda di {$restaurantName} telah Dikonfirmasi!";
    } elseif ($this->reservation->status === 'cancelled') {
        $subject = "Pemberitahuan Pembatalan Reservasi di {$restaurantName}";
    }

    return new Envelope(
        subject: $subject,
    );
    }

    /**
     * Dapatkan konten definisi pesan.
     */
    public function content(): Content
    {
        // Mengarahkan ke file view untuk konten email
        return new Content(
            view: 'emails.reservation-status',
        );
    }

    /**
     * Dapatkan lampiran untuk pesan.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}