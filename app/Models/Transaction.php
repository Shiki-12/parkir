<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'id_lokasi',
        'no_tiket',
        'no_polisi',
        'id_jenis',
        'masuk',
        'keluar',
        'perjam_pertama',
        'perjam_berikutnya',
        'max_perhari',
        'total_jam',
        'total_bayar',
    ];

    protected function casts(): array
    {
        return [
            'masuk' => 'datetime',
            'keluar' => 'datetime',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'id_lokasi');
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'id_jenis');
    }

    /**
     * Calculate parking fee based on plan.md rules.
     */
    public static function calculateFee(
        \Carbon\Carbon|string|\DateTimeInterface $masuk,
        \Carbon\Carbon|string|\DateTimeInterface $keluar,
        int $perjam_pertama,
        int $perjam_berikutnya,
        int $max_perhari,
        ?bool $isTestMode = null
    ): array
    {
        if ($isTestMode === null) {
            $isTestMode = config('parking.test_mode', false);
        }

        $masuk = \Carbon\Carbon::parse($masuk);
        $keluar = \Carbon\Carbon::parse($keluar);

        $diffInSeconds = $masuk->diffInSeconds($keluar);
        if ($diffInSeconds <= 0) {
            $diffInSeconds = 1;
        }

        if ($isTestMode) {
            // diganti jadi 1 menit itu = 1 jam
            $totalHours = (int) ceil($diffInSeconds / 60);
        } else {
            // kalo asli cuy perjamnya
            $diffInMinutes = (int) ceil($diffInSeconds / 60);
            $totalHours = (int) ceil($diffInMinutes / 60);
        }

        $fee = 0;
        if ($totalHours <= 24) {
            $fee = $perjam_pertama + ($perjam_berikutnya * ($totalHours - 1));
            if ($fee > $max_perhari) {
                $fee = $max_perhari;
            }
        } else {
            $totalDays = (int) floor($totalHours / 24);
            $dailyFee = (int) round($max_perhari * 0.6);
            $fee = $dailyFee * $totalDays;
        }

        return [
            'total_hours' => $totalHours,
            'fee' => $fee,
        ];
    }
}
