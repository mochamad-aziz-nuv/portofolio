<?php

namespace Database\Seeders;

use App\Models\Cycle;
use App\Models\FeedingLog;
use App\Models\Pond;
use App\Models\User;
use App\Models\WaterQualityLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users dengan 5 Role Operasional Tambak Lengkap (NagroSmart Standard)
        $usersData = [
            [
                'email' => 'admin@aquafarm.test',
                'name' => 'Mochamad Aziz Noviyanto',
                'phone' => '081335520436',
                'role' => 'admin',
            ],
            [
                'email' => 'supervisor@aquafarm.test',
                'name' => 'Hendra Wijaya, S.Pi',
                'phone' => '081234567801',
                'role' => 'supervisor',
            ],
            [
                'email' => 'teknisi@aquafarm.test',
                'name' => 'Budi Santoso',
                'phone' => '081234567802',
                'role' => 'teknisi_utama',
            ],
            [
                'email' => 'asisten@aquafarm.test',
                'name' => 'Joko Prasetyo',
                'phone' => '081234567803',
                'role' => 'asisten_teknisi',
            ],
            [
                'email' => 'operator@aquafarm.test',
                'name' => 'Dimas Saputra',
                'phone' => '081234567804',
                'role' => 'operator_pakan',
            ],
        ];

        $users = [];
        foreach ($usersData as $u) {
            $users[$u['role']] = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'phone' => $u['phone'],
                    'role' => $u['role'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        $admin = $users['admin'];
        $supervisor = $users['supervisor'];
        $leadTech = $users['teknisi_utama'];
        $fieldAsst = $users['asisten_teknisi'];
        $feedOperator = $users['operator_pakan'];

        // 2. Ponds (5 Kolam)
        $pondsData = [
            [
                'name' => 'Kolam A1 (Intensif HDPE)',
                'code' => 'PL-A1',
                'pond_type' => 'hdpe',
                'length_m' => 30.00,
                'width_m' => 30.00,
                'depth_m' => 1.30,
                'area_sqm' => 900.00,
                'volume_m3' => 1170.00,
                'status' => 'active',
                'location' => 'Blok Utara No. 01',
                'notes' => 'Kolam percontohan sistem bioflok & kincir 4 unit.',
            ],
            [
                'name' => 'Kolam A2 (Intensif HDPE)',
                'code' => 'PL-A2',
                'pond_type' => 'hdpe',
                'length_m' => 30.00,
                'width_m' => 30.00,
                'depth_m' => 1.30,
                'area_sqm' => 900.00,
                'volume_m3' => 1170.00,
                'status' => 'active',
                'location' => 'Blok Utara No. 02',
                'notes' => 'Kolam intensif dengan auto-feeder terintegrasi.',
            ],
            [
                'name' => 'Kolam B1 (Semi-Intensif Beton)',
                'code' => 'PL-B1',
                'pond_type' => 'concrete',
                'length_m' => 25.00,
                'width_m' => 40.00,
                'depth_m' => 1.20,
                'area_sqm' => 1000.00,
                'volume_m3' => 1200.00,
                'status' => 'active',
                'location' => 'Blok Barat No. 01',
                'notes' => 'Dinding beton dasar pasir bersih, aerasi difuser.',
            ],
            [
                'name' => 'Kolam B2 (Semi-Intensif Beton)',
                'code' => 'PL-B2',
                'pond_type' => 'concrete',
                'length_m' => 25.00,
                'width_m' => 40.00,
                'depth_m' => 1.20,
                'area_sqm' => 1000.00,
                'volume_m3' => 1200.00,
                'status' => 'active',
                'location' => 'Blok Barat No. 02',
                'notes' => 'Sistem polyculture ikan & udang.',
            ],
            [
                'name' => 'Kolam C1 (Tandon & Nursery)',
                'code' => 'PL-C1',
                'pond_type' => 'earthen',
                'length_m' => 20.00,
                'width_m' => 25.00,
                'depth_m' => 1.50,
                'area_sqm' => 500.00,
                'volume_m3' => 750.00,
                'status' => 'preparation',
                'location' => 'Blok Timur No. 01',
                'notes' => 'Sedang pengeringan tanah & pengapuran untuk tebar benih minggu depan.',
            ],
        ];

        $ponds = [];
        foreach ($pondsData as $data) {
            $ponds[] = Pond::updateOrCreate(['code' => $data['code']], $data);
        }

        // 3. Cycles untuk kolam yang active
        $cyclesConfig = [
            [
                'pond_id' => $ponds[0]->id,
                'cycle_code' => 'CYC-2024-VNM-A1',
                'species' => 'Udang Vaname (Litopenaeus vannamei)',
                'days_ago' => 35,
                'initial_population' => 125000,
                'target_fcr' => 1.15,
                'target_size' => 40,
                'status' => 'active',
                'notes' => 'Benur F1 SPF dari Hatchery Anyer.',
            ],
            [
                'pond_id' => $ponds[1]->id,
                'cycle_code' => 'CYC-2024-VNM-A2',
                'species' => 'Udang Vaname (Litopenaeus vannamei)',
                'days_ago' => 32,
                'initial_population' => 120000,
                'target_fcr' => 1.18,
                'target_size' => 45,
                'status' => 'active',
                'notes' => 'Penebaran pagi hari, salinitas 22 ppt.',
            ],
            [
                'pond_id' => $ponds[2]->id,
                'cycle_code' => 'CYC-2024-WND-B1',
                'species' => 'Udang Windu (Penaeus monodon)',
                'days_ago' => 40,
                'initial_population' => 85000,
                'target_fcr' => 1.25,
                'target_size' => 30,
                'status' => 'active',
                'notes' => 'Kepadatan moderat, fokus kualitas air stabil.',
            ],
            [
                'pond_id' => $ponds[3]->id,
                'cycle_code' => 'CYC-2024-NLS-B2',
                'species' => 'Ikan Nila Salin (Oreochromis niloticus)',
                'days_ago' => 45,
                'initial_population' => 45000,
                'target_fcr' => 1.30,
                'target_size' => 20,
                'status' => 'active',
                'notes' => 'Benih monosex jantan ukuran 5-7 cm.',
            ],
        ];

        $feedingTimeSlots = ['07:00', '11:00', '15:30', '20:00'];
        $feedBrands = ['Megami #2', 'CP Prima 999', 'Grobest Gold', 'Kaio Pellets'];
        $appetiteOptions = ['optimal', 'optimal', 'optimal', 'normal', 'remaining_feed'];

        $today = Carbon::today();

        foreach ($cyclesConfig as $config) {
            $startDate = $today->copy()->subDays($config['days_ago']);
            $targetHarvest = $startDate->copy()->addDays(90);

            $cycle = Cycle::updateOrCreate(
                ['cycle_code' => $config['cycle_code']],
                [
                    'pond_id' => $config['pond_id'],
                    'species' => $config['species'],
                    'start_date' => $startDate->toDateString(),
                    'target_harvest_date' => $targetHarvest->toDateString(),
                    'initial_population' => $config['initial_population'],
                    'target_fcr' => $config['target_fcr'],
                    'target_size' => $config['target_size'],
                    'status' => $config['status'],
                    'notes' => $config['notes'],
                ]
            );

            // Bersihkan feeding logs lama siklus ini agar tidak duplikat saat re-seed
            $cycle->feedingLogs()->delete();

            // 4. Feeding Logs 30 hari terakhir (Dicatat oleh Operator Pakan & Teknisi)
            for ($d = 29; $d >= 0; $d--) {
                $feedDate = $today->copy()->subDays($d);
                $doc = $startDate->diffInDays($feedDate) + 1;

                $baseFeedKg = 3.5 + ($doc * 0.22) + (rand(-10, 15) / 10.0);
                $baseFeedKg = max(2.0, round($baseFeedKg, 2));

                foreach ($feedingTimeSlots as $idx => $slot) {
                    $slotMultiplier = match ($slot) {
                        '07:00' => 0.85,
                        '11:00' => 0.95,
                        '15:30' => 1.10,
                        '20:00' => 1.10,
                        default => 1.0,
                    };
                    $feedAmount = round($baseFeedKg * $slotMultiplier, 2);
                    $appetite = $appetiteOptions[array_rand($appetiteOptions)];

                    // Jam 07:00 dan 11:00 dicatat Operator Pakan, sore/malam diawasi Teknisi/Supervisor
                    $recorderId = match ($idx) {
                        0, 1 => $feedOperator->id,
                        2 => $leadTech->id,
                        3 => $fieldAsst->id,
                        default => $leadTech->id,
                    };

                    FeedingLog::create([
                        'cycle_id' => $cycle->id,
                        'feed_brand' => $feedBrands[$cycle->id % count($feedBrands)],
                        'feeding_date' => $feedDate->toDateString(),
                        'feeding_time' => $slot,
                        'doc' => (int) $doc,
                        'amount_kg' => $feedAmount,
                        'appetite_rate' => $appetite,
                        'recorded_by' => $recorderId,
                        'notes' => $appetite === 'remaining_feed' ? 'Anco sisa 10%, kurangi porsi berikutnya 1kg.' : null,
                    ]);
                }
            }
        }

        // 5. Water Quality Logs untuk SEMUA 5 Kolam (selama 30 hari terakhir)
        foreach ($ponds as $pond) {
            $activeCycle = $pond->activeCycle;
            $pond->waterQualityLogs()->delete();

            for ($d = 29; $d >= 0; $d--) {
                $logDate = $today->copy()->subDays($d);

                // Morning Reading (Pagi dicatat oleh Asisten Teknisi)
                $morningTemp = round(27.0 + (rand(0, 15) / 10.0), 2);
                $morningPh = round(7.60 + (rand(0, 40) / 100.0), 2);
                $morningDo = round(4.80 + (rand(0, 18) / 10.0), 2);
                $salinity = round(21.0 + (rand(0, 30) / 10.0), 2);
                $turbidity = round(32.0 + rand(0, 12), 2);
                $ammonia = round(0.012 + (rand(0, 25) / 1000.0), 3);
                $alkalinity = round(120.0 + rand(0, 30), 2);

                $morningStatus = 'optimal';
                if ($morningDo < 4.5 || $morningPh < 7.5 || $ammonia > 0.05) {
                    $morningStatus = 'warning';
                }

                WaterQualityLog::create([
                    'pond_id' => $pond->id,
                    'cycle_id' => $activeCycle?->id,
                    'recorded_at' => $logDate->copy()->setTime(6, 30, 0),
                    'temperature' => $morningTemp,
                    'ph' => $morningPh,
                    'dissolved_oxygen' => $morningDo,
                    'salinity' => $salinity,
                    'turbidity' => $turbidity,
                    'ammonia' => $ammonia,
                    'alkalinity' => $alkalinity,
                    'condition_status' => $morningStatus,
                    'recorded_by' => $fieldAsst->id,
                    'notes' => $morningStatus === 'warning' ? 'Aerasi kincir ditambah 1 unit di area outlet.' : null,
                ]);

                // Afternoon Reading (Sore diverifikasi oleh Teknisi Utama)
                $afternoonTemp = round(29.5 + (rand(0, 20) / 10.0), 2);
                $afternoonPh = round(8.10 + (rand(0, 30) / 100.0), 2);
                $afternoonDo = round(6.50 + (rand(0, 20) / 10.0), 2);

                $afternoonStatus = 'optimal';
                if ($afternoonPh > 8.5) {
                    $afternoonStatus = 'warning';
                }

                WaterQualityLog::create([
                    'pond_id' => $pond->id,
                    'cycle_id' => $activeCycle?->id,
                    'recorded_at' => $logDate->copy()->setTime(16, 30, 0),
                    'temperature' => $afternoonTemp,
                    'ph' => $afternoonPh,
                    'dissolved_oxygen' => $afternoonDo,
                    'salinity' => $salinity,
                    'turbidity' => $turbidity,
                    'ammonia' => $ammonia,
                    'alkalinity' => $alkalinity,
                    'condition_status' => $afternoonStatus,
                    'recorded_by' => $leadTech->id,
                    'notes' => 'Pemeriksaan sore hari, kincir stabil.',
                ]);
            }
        }
    }
}
