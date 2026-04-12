<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon; // <-- ADD THIS LINE
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // <-- Tambahkan import untuk Mail
use App\Mail\ReservationStatusUpdated; // <-- Tambahkan import untuk Mailable Anda

class ReservationEditController extends Controller
{
    /**
     * Menampilkan daftar semua reservasi untuk restoran milik admin.
     */
    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        $reservations = $restaurant->reservations()
                                    ->with('user')
                                    ->orderBy('reservation_date', 'asc')
                                    ->orderBy('reservation_time', 'asc')
                                    ->paginate(10);

        // Pastikan ini mengarah ke view Anda
        return view('admin.restaurant.reservation', compact('reservations'));
    }
        // app/Http/Controllers/Admin/ReservationEditController.php

   public function update(Request $request, Reservation $reservation)
    {
        // Keamanan
        if ($reservation->restaurant_id !== Auth::user()->restaurant->id) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // Validasi
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,cancelled,approve_reschedule,reject_reschedule',
        ]);
        
        $action = $validated['status']; // Kita gunakan nama variabel 'action' agar lebih jelas

        // Logika untuk menyetujui reschedule
        if ($action === 'approve_reschedule') {
            // ... (logika approve reschedule Anda yang sudah benar)
            $reservation->update([
                'reservation_date' => $reservation->reschedule_request_date,
                'reservation_time' => $reservation->reschedule_request_time,
                'status' => 'confirmed',
                'reschedule_request_date' => null,
                'reschedule_request_time' => null,
            ]);
        }
        // Logika untuk menolak reschedule
        elseif ($action === 'reject_reschedule') {
            $reservation->update([
                'status' => 'confirmed', // Kembalikan ke status awal
                'reschedule_request_date' => null,
                'reschedule_request_time' => null,
            ]);
        }
        // Logika untuk status lainnya (pending, confirmed, cancelled)
        else {
            $reservation->update([
                'status' => $validated['status']
            ]);
        }
        

        // =====================================================================
        // == BAGIAN YANG HILANG ADA DI SINI: KIRIM EMAIL NOTIFIKASI ==
        // =====================================================================
        try {
            // Mengambil data terbaru dari database sebelum dikirim ke email
            $updatedReservation = $reservation->fresh(); 
            Mail::to($updatedReservation->user->email)->send(new ReservationStatusUpdated($updatedReservation));
        } catch (\Exception $e) {
            // Jika email gagal, jangan hentikan proses. Cukup redirect dengan pesan error tambahan.
            // Di dunia nyata, ini akan dicatat ke log.
            return redirect()->back()->with('success', 'Status reservasi diperbarui, tetapi gagal mengirim email notifikasi.');
        }

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui dan notifikasi telah dikirim.');
    }

    public function destroy(Reservation $reservation)
    {
        // 1. Keamanan: Pastikan reservasi ini milik restoran admin yang login
        if ($reservation->restaurant_id !== Auth::user()->restaurant->id) {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // 2. Hapus data dari database
        $reservation->delete();

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Reservasi telah berhasil dihapus.');
    }
}