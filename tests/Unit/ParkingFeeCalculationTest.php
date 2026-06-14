<?php

namespace Tests\Unit;

use App\Models\Transaction;
use Tests\TestCase;

class ParkingFeeCalculationTest extends TestCase
{
    /**
     * Test production mode (PARKING_TEST_MODE = false)
     */
    public function test_production_mode_durations(): void
    {
        // Real duration 1 minute -> total_jam = 1
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 12:01:00', 2000, 1000, 10000, false);
        $this->assertEquals(1, $res['total_hours']);

        // Real duration 60 minutes -> total_jam = 1
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 13:00:00', 2000, 1000, 10000, false);
        $this->assertEquals(1, $res['total_hours']);

        // Real duration 61 minutes -> total_jam = 2
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 13:01:00', 2000, 1000, 10000, false);
        $this->assertEquals(2, $res['total_hours']);

        // Real duration 121 minutes -> total_jam = 3
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 14:01:00', 2000, 1000, 10000, false);
        $this->assertEquals(3, $res['total_hours']);
    }

    /**
     * Test testing mode (PARKING_TEST_MODE = true)
     */
    public function test_testing_mode_durations(): void
    {
        // Real duration 1 minute -> total_jam = 1
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 12:01:00', 2000, 1000, 10000, true);
        $this->assertEquals(1, $res['total_hours']);

        // Real duration 4 minutes -> total_jam = 4
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 12:04:00', 2000, 1000, 10000, true);
        $this->assertEquals(4, $res['total_hours']);

        // Real duration 4 minutes and 10 seconds -> total_jam = 5 (using rounded-up real minutes)
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 12:04:10', 2000, 1000, 10000, true);
        $this->assertEquals(5, $res['total_hours']);

        // Real duration 15 minutes -> total_jam = 15
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 12:15:00', 2000, 1000, 10000, true);
        $this->assertEquals(15, $res['total_hours']);

        // Real duration 75 minutes -> total_jam = 75
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 13:15:00', 2000, 1000, 10000, true);
        $this->assertEquals(75, $res['total_hours']);

        // Real duration 75 minutes with motorcycle max_per_day Rp 10,000:
        // total_jam = 75
        // total_days = floor(75 / 24) = 3
        // daily_fee = Rp 10,000 * 60% = Rp 6,000
        // total_payment = Rp 6,000 * 3 = Rp 18,000
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 13:15:00', 2000, 1000, 10000, true);
        $this->assertEquals(75, $res['total_hours']);
        $this->assertEquals(18000, $res['fee']);
    }

    /**
     * Test default mode fallback to config setting
     */
    public function test_default_fallback_to_config(): void
    {
        // Set configuration to production (false)
        config(['parking.test_mode' => false]);
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 12:05:00', 2000, 1000, 10000);
        $this->assertEquals(1, $res['total_hours']);

        // Set configuration to test mode (true)
        config(['parking.test_mode' => true]);
        $res = Transaction::calculateFee('2026-06-08 12:00:00', '2026-06-08 12:05:00', 2000, 1000, 10000);
        $this->assertEquals(5, $res['total_hours']);
    }
}
