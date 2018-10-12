<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 2018/10/12
 * Time: 23:16
 */

namespace Subtle\Client\Http;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;
use Subtle\Log\Log;

trait Request
{
    abstract protected function serviceConfig();

    abstract protected function apiConfig($api);

    protected $defaultOptions = [
        'connect_timeout' => 2.0,
        'http_errors' => false,
    ];

    /**
     * @param $api
     * @param array $options
     * @return array|false|string
     * @throws ApiNotSetException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $api, array $options = [])
    {
        $serviceConfig = $this->serviceConfig();
        $apiConfig = $this->apiConfig($api);

        if (null === $apiConfig) {
            throw new ApiNotSetException('API ' . $api . ' not set');
        }

        $baseUri = $apiConfig['base_uri'] ?? $serviceConfig['base_uri'];
        if (! $baseUri) {
            throw new ApiNotSetException('API ' . $api . ' {base_uri} not set');
        }

        $path = $apiConfig['path'] ?? '';

        if (! $path) {
            throw new ApiNotSetException('API ' . $api . ' {path} not set');
        }

        $client = new Client([
            'base_uri' => $baseUri,
            'handler' => $this->handlerStack(),
        ]);

        $request = new \GuzzleHttp\Psr7\Request(
            $apiConfig['method'],
            $path,
            array_replace_recursive(
                $serviceConfig['headers'] ?? [],
                $apiConfig['headers'] ?? [],
                $options['headers'] ?? [])
            );

        $response = $client->send(
            $request,
            array_replace_recursive($serviceConfig, $apiConfig, $options, [
                'on_stats' => [$this, 'onStats']]
            )
        );

        return $this->parseResponse($response);

    }

    /**
     * @param $api
     * @param callable $callback
     * @param array $options
     */
    public function sendAsync($api, callable $callback, array $options = [])
    {

    }

    /**
     * Guzzle middlewares.
     *
     * @return HandlerStack
     */
    protected function handlerStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        $middlewares = [
            Middleware::log(Log::getLogger(), new MessageFormatter(MessageFormatter::SHORT)),
        ];
        if (empty($middlewares)) {
            return $stack;
        }

        foreach ($middlewares as $middleware) {
            $stack->push($middleware);
        }

        return $stack;
    }

    /**
     * Parse response object in child class as needed.
     *
     * @param Response|null $response
     * @return array|false|string
     */
    protected function parseResponse(Response $response = null)
    {
        if (null === $response) {
            return [];
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * Callback function for logging request details.
     *
     * @param TransferStats $stats
     */
    public function onStats(TransferStats $stats): void
    {
            $context = [
                'request_method' => $stats->getRequest()->getMethod(),
                'uri' => $stats->getEffectiveUri()->getScheme() . '://' . $stats->getEffectiveUri()->getHost() . $stats->getEffectiveUri()->getPath(),
                'time' => $stats->getTransferTime(),
            ];
            if ($stats->hasResponse()) {
                $statusCode = $stats->getResponse()->getStatusCode();
                $context['status'] = $statusCode;

                if ($statusCode >= 200 && $statusCode < 300) {
                    Log::info('HTTP REQUEST', $context);
                }

                if ($statusCode >= 300 && $statusCode < 400) {
                    Log::notice('HTTP REQUEST', $context);
                }

                if ($statusCode >= 400 && $statusCode < 500) {
                    $context['request_query'] = $stats->getEffectiveUri()->getQuery();
                    $context['request_user_info'] = $stats->getEffectiveUri()->getUserInfo();
                    $context['request_body'] = $stats->getRequest()->getBody();
                    $context['request_headers'] = $stats->getRequest()->getHeaders();

                    $context['response_headers'] = $stats->getResponse()->getHeaders();
                    $context['response_body'] = $stats->getResponse()->getBody();
                    Log::warning('HTTP REQUEST', $context);
                }

                if ($statusCode >= 500) {
                    $context['request_query'] = $stats->getEffectiveUri()->getQuery();
                    $context['request_user_info'] = $stats->getEffectiveUri()->getUserInfo();
                    $context['request_body'] = $stats->getRequest()->getBody();
                    $context['request_headers'] = $stats->getRequest()->getHeaders();

                    $context['response_headers'] = $stats->getResponse()->getHeaders();
                    $context['response_body'] = $stats->getResponse()->getBody();
                    Log::error('HTTP REQUEST', $context);
                }

            } else {
                $context['error_code'] = $stats->getHandlerErrorData();
                Log::critical('HTTP REQUEST', $context);
            }
    }
}