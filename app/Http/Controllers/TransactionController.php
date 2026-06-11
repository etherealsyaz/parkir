<?php

namespace App\Http\Controllers;
use App\Models\Location;
use App\Models\VehicleType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index()
    {
        $locations    = Location::all();
        $vehicleTypes = VehicleType::all();
        $tickets      = Transaction::with(['location','vehicleType'])
                            ->whereNull('keluar')
                            ->orderByDesc('masuk')->get();
        return view('transaction.index', compact('locations','vehicleTypes','tickets'));
    }

    /**
     * Kendaraan MASUK: buat tiket baru
     */
    public function enterVehicle(Request $request)
    {
        $request->validate([
            'id_lokasi' => 'required|exists:locations,id',
            'id_jenis'  => 'required|exists:vehicle__types,id',
        ]);

        $location = Location::findOrFail($request->id_lokasi);
        $vehicle  = VehicleType::findOrFail($request->id_jenis);

        // Cek kapasitas masih ada
        $kolom = $this->getKolomKapasitas($vehicle->jenis);
        if ($location->$kolom <= 0) {
            return back()->with('error', 'Kapasitas penuh!');
        }

        // Kurangi kapasitas
        $location->decrement($kolom);

        // Generate No Tiket: YYYYMMDDHHMMSS + random 2 digit
        $noTiket = now()->format('YmdHis') . rand(10, 99);

        $trx = Transaction::create([
            'id_lokasi'         => $location->id,
            'no_tiket'          => $noTiket,
            'id_jenis'          => $vehicle->id,
            'masuk'             => now(),
            'perjam_pertama'    => $vehicle->perjam_pertama,
            'perjam_berikutnya' => $vehicle->perjam_berikutnya,
            'max_perhari'       => $vehicle->max_perhari,
        ]);

        // Generate PDF tiket dan simpan ke storage
        $this->generateTicketPDF($trx);

        return back()->with('ticket', $noTiket);
    }

    /**
     * Kendaraan KELUAR: hitung biaya parkir
     */
    public function exitVehicle(Request $request)
    {
        $request->validate([
            'no_tiket'  => 'required|string',
            'no_polisi' => 'required|string|max:15',
        ]);

        $trx = Transaction::where('no_tiket', $request->no_tiket)
                    ->whereNull('keluar')->firstOrFail();

        $masuk  = Carbon::parse($trx->masuk);
        $keluar = now();
        $totalJam = $masuk->diffInMinutes($keluar); // 1 menit = 1 jam

        $totalBayar = $this->hitungBiayaParkir(
            $totalJam,
            $trx->perjam_pertama,
            $trx->perjam_berikutnya,
            $trx->max_perhari
        );

        // Update transaksi
        $trx->update([
            'no_polisi'  => $request->no_polisi,
            'keluar'     => $keluar,
            'total_jam'  => $totalJam,
            'total_bayar'=> $totalBayar,
        ]);

        // Kembalikan kapasitas
        $location = $trx->location;
        $kolom = $this->getKolomKapasitas($trx->vehicleType->jenis);
        $location->increment($kolom);

        return back()->with('total_bayar', 'Rp ' . number_format($totalBayar, 0, ',', '.'));
    }

    /**
     * Rumus perhitungan biaya parkir
     */
    private function hitungBiayaParkir(int $jam, int $pertama, int $berikutnya, int $maxPerhari): int
    {
        if ($jam <= 0) return $pertama;

        if ($jam <= 24) {
            // Dalam 1 hari
            $total = $pertama + ($berikutnya * ($jam - 1));
            return min($total, $maxPerhari);
        } else {
            // Lebih dari 1 hari
            $hari = ceil($jam / 24);
            $perhari = $maxPerhari * 0.6;
            return (int)($hari * $perhari);
        }
    }

    /**
     * Mapping jenis kendaraan ke kolom kapasitas di tabel locations
     */
    private function getKolomKapasitas(string $jenis): string
    {
        return match($jenis) {
            'motorcycle' => 'max_motorcycle',
            'car'        => 'max_car',
            default      => 'max_other',
        };
    }

    /**
     * Generate PDF Tiket Parkir
     */
    private function generateTicketPDF(Transaction $trx): void
    {
        $pdf = Pdf::loadView('ticket.pdf', compact('trx'));
        $path = storage_path('app/public/tickets/' . $trx->no_tiket . '.pdf');
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        $pdf->save($path);
    }

    public function viewTicket(string $noTiket)
    {
        $path = storage_path('app/public/tickets/' . $noTiket . '.pdf');
        if (!file_exists($path)) abort(404);
        return response()->file($path);
    }

    public function allTransactions()
    {
        $transactions = Transaction::with(['location','vehicleType'])->orderByDesc('masuk')->get();
        return view('transaction.all', compact('transactions'));
    }

   public function report()
{
    $transactions = Transaction::with(['location', 'vehicleType'])
                    ->whereNotNull('keluar')
                    ->orderByDesc('masuk')
                    ->get();

    return view('transaction.all', compact('transactions'));
}
}
