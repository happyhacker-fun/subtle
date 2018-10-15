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
    protected $defaultOptions = [
        'timeout' => 2,
        'http_errors' => false,
    ];
    /**
     * @internal
     * @var string
     */
    private $module = 'HTTP REQUEST';

    /**
     * @param $api
     * @param array $options
     * @return array|false|string|Response
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
        if (!$baseUri) {
            throw new ApiNotSetException('API ' . $api . ' {base_uri} not set');
        }

        $path = $apiConfig['path'] ?? '';

        if (!$path) {
            throw new ApiNotSetException('API ' . $api . ' {path} not set');
        }

        $mergedConfig = array_replace_recursive($this->defaultOptions, $serviceConfig, $apiConfig, $options);
        Log::info('config', $mergedConfig);

        $handlers = $mergedConfig['handlers'] ?? [];
        $client = new Client([
            'base_uri' => $baseUri,
            'handler' => $this->handlerStack($handlers),
        ]);

        $request = new \GuzzleHttp\Psr7\Request(
            $apiConfig['method'],
            $path,
            $mergedConfig['headers'] ?? []
        );

        $response =  $client->send(
            $request,
            array_merge($mergedConfig, ['on_stats' => [$this, 'onStats']])
        );

        $responseHandler = $mergedConfig['response_handler'] ?? null;
        if (!\is_callable($responseHandler)) {
            Log::info($responseHandler);
        }

        if (! $responseHandler) {
            return $response;
        }

        if ($responseHandler === 'default') {
            return $this->parseResponse($response);
        }

        if (\is_callable($responseHandler)) {
            return $responseHandler($response);
        }

        return $response;
    }

    /**
     * Guzzle middlewares.
     *
     * @param array $handlers
     * @return HandlerStack
     */
    protected function handlerStack(array $handlers): HandlerStack
    {
        $stack = HandlerStack::create();

        $handlers[] = Middleware::log(Log::getLogger(), new MessageFormatter(MessageFormatter::SHORT));

        foreach ($handlers as $middleware) {
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
     * @param $api
     * @param callable $callback
     * @param array $options
     */
    public function sendAsync($api, callable $callback, array $options = [])
    {

    }

    /**
     * Callback function for logging request details.
     *
     * @param TransferStats $stats
     */
    public function onStats(TransferStats $stats): void
    {
        $context = [
            'method' => $stats->getRequest()->getMethod(),
            'uri' => $stats->getEffectiveUri()->getScheme() . '://' . $stats->getEffectiveUri()->getHost() . $stats->getEffectiveUri()->getPath(),
            'time' => $stats->getTransferTime(),
        ];
        if ($stats->hasResponse()) {
            $statusCode = $stats->getResponse()->getStatusCode();
            $context['status'] = $statusCode;

            if ($statusCode >= 200 && $statusCode < 300) {
                Log::info($this->module, $context);
            }

            if ($statusCode >= 300 && $statusCode < 400) {
                Log::notice($this->module, $context);
            }

            if ($statusCode >= 400 && $statusCode < 500) {
                $context['request_query'] = $stats->getEffectiveUri()->getQuery();
                $context['request_user_info'] = $stats->getEffectiveUri()->getUserInfo();
                $context['request_body'] = $stats->getRequest()->getBody();
                $context['request_headers'] = $stats->getRequest()->getHeaders();

                $context['response_headers'] = $stats->getResponse()->getHeaders();
                $context['response_body'] = $stats->getResponse()->getBody();
                Log::warning($this->module, $context);
            }

            if ($statusCode >= 500) {
                $context['request_query'] = $stats->getEffectiveUri()->getQuery();
                $context['request_user_info'] = $stats->getEffectiveUri()->getUserInfo();
                $context['request_body'] = $stats->getRequest()->getBody();
                $context['request_headers'] = $stats->getRequest()->getHeaders();

                $context['response_headers'] = $stats->getResponse()->getHeaders();
                $context['response_body'] = $stats->getResponse()->getBody();
                Log::error($this->module, $context);
            }

        } else {
            $context['error_code'] = $stats->getHandlerErrorData();
            Log::critical($this->module, $context);
        }
    }

    abstract protected function serviceConfig(): array;

    abstract protected function apiConfig($api): array;
}