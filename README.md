# Denk – a small helper package to work with the OpenAI API

The package name is derived from the German word "denken" which means "to think".

## Installation

```bash
composer require timkley/denk
```

## Usage

Make sure that you have an OpenAI API key. You can get one [here](https://platform.openai.com/signup).

The package assumes that you've set the `OPENAI_API_KEY` environment variable.

### Generating text

To generate text, use the `Denk::text()` method. It uses the `chat` endpoint of the OpenAI API.

```php
use Denk\Denk;

// using only a simple prompt
$text = Denk::text()->prompt('Once upon a time')->generate();

// set a system prompt
$text = Denk::text()
    ->prompt('Once upon a time')
    ->systemPrompt('Write as a pirate')
    ->generate();
    
// Manually provide messages

use Denk\Messages\SystemMessage;
use Denk\Messages\UserMessage;

$text = Denk::text()
    ->messages([
        new SystemMessage('Write as a pirate'),
        new UserMessage('Once upon a time'),
    ])
    ->generate();
```

#### Available models

- `gpt-4o-mini`
- `gpt-4o`
- `gpt-4-turbo`
- `gpt-4`
- `gpt-3.5-turbo`

### Generating JSON

To generate [Structured Output](https://platform.openai.com/docs/guides/structured-outputs), you can use the `Denk::json()` method. Set the properties of the JSON object and provide a prompt, additionally you may also set a system prompt.

```php
use Denk\Denk;

$json = Denk::json()
    ->properties([
        'title' => [
            'type' => 'string',
            'description' => 'The title of the story',
        ],
        'story' => [
            'type' => 'string',
            'description' => 'The story itself',
        ],
    ])
    ->prompt('Write a store about Chewbacca winning a game against Han Solo')
    ->generate();
```

#### Available models

- `gpt-4o`
- `gpt-4o-mini`

### Generating Images

To generate images, you can use the `Denk::image()` method. Set the properties of the image and provide a prompt.

```php
use Denk\Denk;

$image = Denk::image()
    ->prompt('A beautiful sunset over the ocean')
    ->size('square')
    ->quality('standard')
    ->generate();
```

#### Available image sizes

- `square`
- `landscape`
- `portrait`

#### Available image qualities

- `standard`
- `hd`