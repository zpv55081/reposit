<?php

namespace App\BTU\TAS\Services\Btu;

use App\BTU\TAS\Models\Temporary;
use Illuminate\Support\Facades\Http;

/**
 * Сервис соединения с БТУ
 * (для api-взаимодействия c btu.k-telecom)
 */
class ConnectorBtuService
{
    /**
     * Интервьюировать php-приложение btu
     * @param string $api_path http-запросный uri
     * @param array $get_parameters передаваемые в запрос параметры
     * @return array btu контент или информация об ошибках 
     */
    public function interview(string $api_path, array $get_parameters = []): array
    {
        // попытка интервью с авторизацией по ранее сохранённому sessid
        $result_by_sid = Http::withHeaders([
            'Cookie' => 'PHPSESSID=' . Temporary::where('id', 10)->first()->value
        ])->get($api_path, $get_parameters);

        // если попытка авторизации по sid была успешной 
        if (isset($result_by_sid->headers()['Authenticated'][0])) {
            // пересохраняем возвращённый key
            Temporary::where('id', 10)
                ->update(['value' => $result_by_sid->headers()['ProspectiveKey'][0]]);
            // отдаём запрашивавшиеся данные
            return $result_by_sid->json();
        } else {
            // делаем попытку интервью с авторизацией по логин-паролю
            $result_by_lop = Http::get($api_path, array_merge([
                'login' => config('btu_tas.btu.login'),
                'pass' => config('btu_tas.btu.password')
            ], $get_parameters));
            // если попытка авторизации по логин-паролю удалась 
            if (isset($result_by_lop->headers()['Authenticated'][0])) {
                // пересохраняем возвращённый key
                Temporary::where('id', 10)
                    ->update(['value' => $result_by_lop->headers()['ProspectiveKey'][0]]);
                // отдаём запрашивавшиеся данные
                return $result_by_lop->json();
            } else {
                // возвращаем информацию о проблеме с btu-аутентификацией
                return [
                    'status' => 'failed',
                    'errors' => ['supplier_answer_mistakes' => $result_by_lop->headers()['Mistakes']]
                ];
            }
        }

        // возвращаем информацию о некачественности изначального запроса
        return [
            'status' => 'failed',
            'errors' => ['Relevant data missing']
        ];
    }
}
