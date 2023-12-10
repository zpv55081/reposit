Для начала использования нужно скачать каталог vehicles_shop

Запустить контейнеры docker-compose up --build -d

Зайти в контейнер docker exec -it vehicles_shop_piha_1 bash
Установить фреймворк composer install

Запустить миграции таблиц БД php bin/console doctrine:migrations:migrate


ПРИМЕРЫ РАБОТЫ API:

GET-запрос данных витрины автомобилей
http://localhost:800/api/showcase

GET-запрос подгрузки кредитных программ
http://localhost:800/api/evaluate_credit_program?price=1000000&init=300000&month=10000&term=36

POST-запрос отправки заявки на кредит (PHP-Guzzle)
$client = new Client();
$options = [
  'multipart' => [
    [
      'name' => 'showcase_id',
      'contents' => '2'
    ],
    [
      'name' => 'programm_amount',
      'contents' => '385255'
    ],
    [
      'name' => 'rate',
      'contents' => '12.3'
    ],
    [
      'name' => 'vehicle_price',
      'contents' => '800000'
    ],
    [
      'name' => 'initial_payment',
      'contents' => '500000'
    ],
    [
      'name' => 'monthly_payment',
      'contents' => '9800'
    ],
    [
      'name' => 'credit_term',
      'contents' => '36'
    ]
]];
$request = new Request('POST', 'http://localhost:800/api/credit_asking');
$res = $client->sendAsync($request, $options)->wait();
echo $res->getBody();
