<?php

namespace LaravelSmsRu;

use DomainException;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use LaravelSmsRu\Exceptions\ResponseException;

/**
 *
 */
class SmsRuApi {

    /**
     * @var HttpClient
     */
    protected HttpClient $client;

    /**
     * @var string|null
     */
    protected ?string $api_key;

    /**
     * @var string|null
     */
    protected ?string $sender;

    /**
     * @var int
     */
    protected int $translit;

    /**
     * @var int
     */
    protected int $test;

    /**
     * @var int|null
     */
    protected ?int $partner_id;

    /**
     * @param array $config
     * @param $client
     */
    public function __construct(array $config, $client)
    {
        $this->api_key = $config['api_key'] ?? null;
        $this->sender = $config['sender'] ?? null;
        $this->translit = $config['translit'] ?? 0;
        $this->test = $config['test'] ?? 0;
        $this->partner_id = $config['partner_id'] ?? null;
        $this->client = $client;
    }

    /**
     * Совершает отправку СМС сообщения одному или нескольким получателям.
     *
     * @param array $params
     *      $params['to'] string - Номер телефона получателя (либо несколько номеров, через запятую — до 100 штук за один запрос).
     *          Если вы указываете несколько номеров и один из них указан неверно, то на остальные номера сообщения также не отправляются, и возвращается код ошибки.
     *      $params['msg'] string - Текст сообщения в кодировке UTF-8
     *      $params['multi'] array('номер получателя' => 'текст сообщения') - Если вы хотите в одном запросе отправить разные сообщения на несколько номеров,
     *          то воспользуйтесь этим параметром (до 100 сообщений за 1 запрос).
     *          В этом случае, параметры to и text использовать не нужно
     *      $params['time'] Если вам нужна отложенная отправка, то укажите время отправки.
     *          Указывается в формате UNIX TIME (пример: 1280307978).
     *          Должно быть не больше 7 дней с момента подачи запроса. Если время меньше текущего времени, сообщение отправляется моментально.
     *      $params['translit'] = 1 - Переводит все русские символы в латинские. (по умолчанию 0)
     *
     * @return array
     * @throws ResponseException|GuzzleException
     */
    public function send(array $params): array
    {
        $base = [
            'api_id' => $this->api_key,
            'from' => $this->sender,
            'translit' => $this->translit,
            'test' => $this->test,
            'partner_id' => $this->partner_id,
            'json' => 1
        ];

        $params = http_build_query(array_merge($base, array_filter($params)));

        try {
            $response = $this->client->request('POST', 'https://sms.ru/sms/send'.$params);
            $response = json_decode((string) $response->getBody(), true);

            if ($response['status'] == "ERROR") {
                throw new DomainException($response['status_text'], $response['status_code']);
            }

            if ($response['status'] == "OK") {
                foreach ($response['sms'] as $data) {
                    if ($data['status'] != "OK") {
                        throw new DomainException($data['status_text'], $data['status_code']);
                    }
                }
            }

            return $response;
        } catch (DomainException $exception) {
            throw ResponseException::respondedWithAnError($exception);
        } catch (Exception $exception) {
            throw ResponseException::connectionError($exception);
        }
    }
}