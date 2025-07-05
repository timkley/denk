<?php

declare(strict_types=1);

namespace Denk\Generators;

use Denk\Concerns\TextPrompts;
use Denk\Exceptions\DenkException;
use OpenAI\Responses\Chat\CreateStreamedResponse;
use OpenAI\Responses\StreamResponse;

class TextGenerator extends Generator
{
    use TextPrompts;

    protected ?string $model = 'gpt-4o-mini';

    public function model(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function generate(): string
    {
        if (is_null($this->messages) || $this->messages->isEmpty()) {
            throw DenkException::noMessages();
        }

        $data = $this->client->chat()->create([
            'model' => $this->model,
            'temperature' => $this->temperature,
            'messages' => $this->messages->toArray(),
        ]);

        if (data_get($data, 'error')) {
            /** @var string $error_type */
            $error_type = data_get($data, 'error.type', 'unknown');
            /** @var string $error_message */
            $error_message = data_get($data, 'error.message', 'unknown');

            throw DenkException::openAiError(
                vsprintf(
                    'OpenAI Error:  [%s] %s',
                    [
                        $error_type,
                        $error_message,
                    ]
                )
            );
        }

        /** @var string $content */
        $content = data_get($data, 'choices.0.message.content');

        return $content;
    }

    /**
     * Generate a streamed response.
     *
     * @return StreamResponse<CreateStreamedResponse>
     */
    public function generateStreamed(): StreamResponse
    {
        if (is_null($this->messages) || $this->messages->isEmpty()) {
            throw DenkException::noMessages();
        }

        return $this->client->chat()->createStreamed([
            'model' => $this->model,
            'temperature' => $this->temperature,
            'messages' => $this->messages->toArray(),
        ]);
    }
}
