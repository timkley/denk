<?php

declare(strict_types=1);

namespace Denk\Generators;

use Denk\Concerns\TextPrompts;
use Denk\Exceptions\DenkException;
use OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class JsonGenerator extends Generator
{
    use TextPrompts;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $properties = null;

    protected ?string $model = 'gpt-4o-mini';

    public function model(string $model): self
    {
        if (! in_array($model, ['gpt-4o-mini', 'gpt-4o'])) {
            throw DenkException::invalidModel($model);
        }

        $this->model = $model;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public function properties(array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function generate(): array
    {
        if ($this->messages?->isEmpty()) {
            throw DenkException::noMessages();
        }

        if (is_null($this->properties)) {
            throw DenkException::noJsonModel();
        }

        $responseFormat = [
            'type' => 'json_schema',
            'json_schema' => [
                'name' => 'test',
                'strict' => true,
                'schema' => [
                    'type' => 'object',
                    'properties' => $this->properties,
                    'required' => array_keys($this->properties),
                    'additionalProperties' => false,
                ],
            ],
        ];

        $data = $this->client->chat()->create([
            'model' => $this->model,
            'messages' => $this->messages?->toArray(),
            'response_format' => $responseFormat,
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
        $content = data_get($data, 'choices.0.message.content', '{}');

        /** @var array<string, mixed> $json */
        $json = json_decode($content, associative: true, flags: JSON_THROW_ON_ERROR);

        return $json;
    }

    /**
     * @param  array<CreateResponse>  $responses
     */
    public static function fake(array $responses = []): self
    {
        if (empty($responses)) {
            $responses = [
                CreateResponse::fake([
                    'choices' => [
                        [
                            'message' => [
                                'content' => json_encode([
                                    'title' => 'Fake title',
                                    'description' => 'Fake description',
                                ]),
                            ],
                        ],
                    ],
                ]),
            ];
        }

        return new self(new OpenAI\Testing\ClientFake($responses));
    }
}