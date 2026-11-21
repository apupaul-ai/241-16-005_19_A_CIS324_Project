<?php
// Shared cost rules used by the estimator and the budget planner.
class CostCalculator
{
    public const ROAD_FACTOR = 1.3;   // roads are longer than a straight line
    public const HOTEL_SHARE = 0.45;  // share of a day's average cost spent on a double room
    public const FOOD_SHARE  = 0.35;
    public const LOCAL_SHARE = 0.20;  // local rides + entry tickets
    public const EXTRAS_SHARE = 0.10;
    public const BUFFER_SHARE = 0.10;
    public const PEAK_FACTOR  = 1.2;

    public const STYLES = [
        'budget'   => ['label' => 'Budget',   'mult' => 0.7],
        'standard' => ['label' => 'Standard', 'mult' => 1.0],
        'comfort'  => ['label' => 'Comfort',  'mult' => 1.6],
    ];

    public static function roadKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $r = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        $straight = 2 * $r * asin(sqrt($a));
        return max(20.0, $straight * self::ROAD_FACTOR);
    }

    public static function straightKm(float $roadKm): float
    {
        return $roadKm / self::ROAD_FACTOR;
    }

    // Costs at the destination: hotel, food and local travel/tickets.
    public static function onTheGround(float $avgDaily, int $days, int $travelers, string $style, bool $peak): array
    {
        $mult   = self::STYLES[$style]['mult'];
        $nights = max(0, $days - 1);
        $rooms  = (int) ceil($travelers / 2);           // two people share one room
        $roomPerNight = 2 * self::HOTEL_SHARE * $avgDaily * $mult * ($peak ? self::PEAK_FACTOR : 1.0);

        return [
            'nights' => $nights,
            'rooms'  => $rooms,
            'hotel'  => round($rooms * $nights * $roomPerNight),
            'food'   => round(self::FOOD_SHARE * $avgDaily * $mult * $days * $travelers),
            'local'  => round(self::LOCAL_SHARE * $avgDaily * $days * $travelers),
        ];
    }

    public static function margins(float $subtotal): array
    {
        return [
            'extras' => round($subtotal * self::EXTRAS_SHARE),
            'buffer' => round($subtotal * self::BUFFER_SHARE),
        ];
    }
}