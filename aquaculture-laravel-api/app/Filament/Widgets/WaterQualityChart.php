<?php

namespace App\Filament\Widgets;

use App\Models\WaterQualityLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class WaterQualityChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Parameter Kualitas Air (7 Hari Terakhir)';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $dates = collect();
        $doData = collect();
        $tempData = collect();
        $phData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates->push($date->format('d M'));

            $avgDo = WaterQualityLog::whereDate('recorded_at', $date->toDateString())->avg('dissolved_oxygen');
            $avgTemp = WaterQualityLog::whereDate('recorded_at', $date->toDateString())->avg('temperature');
            $avgPh = WaterQualityLog::whereDate('recorded_at', $date->toDateString())->avg('ph');

            $doData->push(round($avgDo ?? 5.5, 2));
            $tempData->push(round($avgTemp ?? 28.5, 2));
            $phData->push(round($avgPh ?? 7.8, 2));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Dissolved Oxygen / DO (mg/L)',
                    'data' => $doData->toArray(),
                    'borderColor' => '#0ea5e9', // Sky blue
                    'backgroundColor' => 'rgba(14, 165, 233, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'pH Level',
                    'data' => $phData->toArray(),
                    'borderColor' => '#10b981', // Emerald green
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Suhu Air (°C)',
                    'data' => $tempData->toArray(),
                    'borderColor' => '#f59e0b', // Amber
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $dates->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
