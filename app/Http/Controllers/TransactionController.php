<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Transaction;
use App\Models\VehicleType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        $vehicleTypes = VehicleType::all();
        $activeTickets = Transaction::with(['location', 'vehicleType'])
            ->whereNull('keluar')
            ->latest('masuk')
            ->get();

        $availableCapacity = [];
        foreach ($locations as $location) {
            $motorcycleCount = Transaction::where('id_lokasi', $location->id)
                ->whereNull('keluar')
                ->whereHas('vehicleType', fn($q) => $q->where('jenis', 'motorcycle'))
                ->count();

            $carCount = Transaction::where('id_lokasi', $location->id)
                ->whereNull('keluar')
                ->whereHas('vehicleType', fn($q) => $q->where('jenis', 'car'))
                ->count();

            $otherCount = Transaction::where('id_lokasi', $location->id)
                ->whereNull('keluar')
                ->whereHas('vehicleType', fn($q) => $q->where('jenis', 'other'))
                ->count();

            $availableCapacity[$location->id] = [
                'motorcycle' => $location->max_motorcycle - $motorcycleCount,
                'car' => $location->max_car - $carCount,
                'other' => $location->max_other - $otherCount,
            ];
        }

        $allTransactions = Transaction::with(['location', 'vehicleType'])->latest('masuk')->get();

        return view('transactions.index', [
            'title' => 'Transaction',
            'locations' => $locations,
            'vehicleTypes' => $vehicleTypes,
            'activeTickets' => $activeTickets,
            'availableCapacity' => $availableCapacity,
            'allTransactions' => $allTransactions,
        ]);
    }

    public function enter(Request $request)
    {
        $request->validate([
            'id_lokasi' => 'required|exists:locations,id',
            'id_jenis' => 'required|exists:vehicle_types,id',
            'no_polisi' => 'required|string|max:15',
        ]);

        $location = Location::findOrFail($request->id_lokasi);
        $vehicleType = VehicleType::findOrFail($request->id_jenis);

        $capacityMap = [
            'motorcycle' => 'max_motorcycle',
            'car' => 'max_car',
            'other' => 'max_other',
        ];

        $capacityField = $capacityMap[$vehicleType->jenis];
        $maxCapacity = $location->{$capacityField};

        $activeCount = Transaction::where('id_lokasi', $location->id)
            ->whereNull('keluar')
            ->whereHas('vehicleType', fn($q) => $q->where('jenis', $vehicleType->jenis))
            ->count();

        if ($activeCount >= $maxCapacity) {
            return redirect()->back()
                ->with('error', 'Parking capacity is full for this vehicle type at this location!')
                ->withInput();
        }

        $transaction = Transaction::create([
            'id_lokasi' => $location->id,
            'no_tiket' => '',
            'no_polisi' => $request->no_polisi,
            'id_jenis' => $vehicleType->id,
            'masuk' => Carbon::now(),
            'perjam_pertama' => $vehicleType->perjam_pertama,
            'perjam_berikutnya' => $vehicleType->perjam_berikutnya,
            'max_perhari' => $vehicleType->max_perhari,
        ]);

        $noTiket = date('YmdHis') . $transaction->id;
        $transaction->update(['no_tiket' => $noTiket]);

        $pdf = Pdf::loadView('tickets.pdf', [
            'location' => $location,
            'vehicleType' => $vehicleType,
            'transaction' => $transaction,
        ]);

        Storage::disk('public')->makeDirectory('tickets');
        Storage::disk('public')->put("tickets/{$noTiket}.pdf", $pdf->output());

        return redirect()->back()
            ->with('success', "Vehicle entered successfully! Ticket number: {$noTiket}")
            ->with('ticket_number', $noTiket);
    }

    public function exit(Request $request)
    {
        $request->validate([
            'no_tiket' => 'required|string',
            'no_polisi' => 'required|string|max:15',
        ]);

        $transaction = Transaction::where('no_tiket', $request->no_tiket)
            ->whereNull('keluar')
            ->first();

        if (!$transaction) {
            return redirect()->back()
                ->with('error', 'Ticket not found or already exited!')
                ->withInput();
        }

        $masuk = Carbon::parse($transaction->masuk);
        $keluar = Carbon::now();

        $calculation = Transaction::calculateFee(
            $masuk,
            $keluar,
            $transaction->perjam_pertama,
            $transaction->perjam_berikutnya,
            $transaction->max_perhari
        );

        $totalHours = $calculation['total_hours'];
        $fee = $calculation['fee'];

        $transaction->update([
            'keluar' => $keluar,
            'no_polisi' => $request->no_polisi,
            'total_jam' => $totalHours,
            'total_bayar' => $fee,
        ]);

        return redirect()->back()
            ->with('success', "Vehicle exited successfully! Total fee: Rp " . number_format($fee, 0, ',', '.'))
            ->with('total_bayar', $fee);
    }


    public function allTransactions()
    {
        $transactions = Transaction::with(['location', 'vehicleType'])
            ->latest('masuk')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'transactions' => $transactions,
            ]);
        }

        return view('transactions.all', [
            'title' => 'All Transactions',
            'transactions' => $transactions,
        ]);
    }

    public function downloadTicket(string $noTiket)
    {
        $path = "tickets/{$noTiket}.pdf";

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Ticket not found.');
        }

        return response()->file(Storage::disk('public')->path($path));
    }
}
