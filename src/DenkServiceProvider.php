<?php

declare(strict_types=1);

namespace Denk;

use Illuminate\Support\ServiceProvider;
use OpenAI;

class DenkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/denk.php' => config_path('denk.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->app->bind(DenkService::class, function () {
            $apiKey = config('denk.openai_api_key');

            if (! is_string($apiKey) || empty($apiKey)) {
                throw new \Exception('OpenAI API key is not set');
            }

            $client = OpenAI::factory()
                ->withApiKey($apiKey)
                ->withBaseUri(config()->string('denk.base_uri', 'api.openai.com/v1'))
                ->make();

            return new DenkService($client);
        });
    }

    public static function fake(array $responses = []): void
    {
        app()->instance(DenkService::class, new DenkFake($responses));
    }
}
