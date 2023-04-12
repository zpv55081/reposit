<?php

namespace App\BTU\TAS\Http\Controllers\API\Indicator;

use App\BTU\TAS\Services\Btu\ConnectorBtuService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Контроллер ключевых показателей
 */
class IndicatorController extends Controller
{
    /**
     * Разместить сведения о показателях в БТУ
     * @param Illuminate\Http\Request $request
     * @return array результат размещения
     */
    public function placeToBtu(Request $request, ConnectorBtuService $service): array
    {
        return $service->interview(
            config('btu_tas.btu.api_path') . '/indicator/place',
            $request->all()
        );
    }

    /**
     * Взять сведения о показателях из БТУ
     * @param Illuminate\Http\Request $request
     * @return array данные показателей или информация об ошибках 
     */
    public function takeFromBtu(Request $request, ConnectorBtuService $service): array
    {
        return $service->interview(
            config('btu_tas.btu.api_path') . '/indicator/take',
            $request->all()
        );
    }
}
