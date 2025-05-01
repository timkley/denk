# Denk – a small helper package to work with the OpenAI API

The package name is derived from the German word "denken" which means "to think".

## Installation

```bash
composer require timkley/denk
```

## How to use

Make sure that you have an OpenAI API key. You can get one [here](https://platform.openai.com/signup).

### Initialization

#### Using Laravel

The package comes with a service provider that will automatically register the OpenAI client. You can use the `Denk` facade to access the service.

Make sure to publish the configuration file to set your API key and/or change the API url.

```bash
php artisan vendor:publish --provider="Denk\DenkServiceProvider"
```

After that, you can use the `Denk` facade to access the service like this:

```php
use Denk\Facades\Denk;

$text = Denk::text()->prompt('Once upon a time')->generate();
```

#### Manually or non-Laravel projects

To initialize the service, you need create an OpenAI client and pass it to the DenkService.

```php
use Denk\DenkService;
use OpenAI;

$client = OpenAI::client('your-api-key');
$denk = new DenkService($client);
```

### Generating text

```php
use Denk\DenkService;
use OpenAI;

$client = OpenAI::client('your-api-key');
$denk = new DenkService($client);

// using only a simple prompt
$text = $denk->text()->prompt('Once upon a time')->generate();

// Manually provide messages
use Denk\Messages\DeveloperMessage;
use Denk\Messages\UserMessage;

$text = $denk->text()
    ->messages([
        new DeveloperMessage('Write as a pirate'),
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

To generate [Structured Output](https://platform.openai.com/docs/guides/structured-outputs), you can use the `json()` method. Set the properties of the JSON object and provide a prompt, additionally you may also set a system prompt.

```php
use Denk\DenkService;
use OpenAI;

$client = OpenAI::client('your-api-key');
$denk = new DenkService($client);

$json = $denk->json()
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

To generate images, you can use the `image()` method. Set the properties of the image and provide a prompt.

```php
use Denk\DenkService;
use OpenAI;

$client = OpenAI::client('your-api-key');
$denk = new DenkService($client);

$image = $denk->image()
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