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

            return new DenkService(OpenAI::client($apiKey));
        });
    }
}
