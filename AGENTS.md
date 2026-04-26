# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Denk is a lightweight PHP package for working with the OpenAI API. It provides a fluent, chainable interface for text generation, structured JSON output, and image generation.

## Commands

```bash
composer test       # Run tests (Pest)
composer phpstan    # Static analysis (level 9)
composer pint       # Code formatting (Laravel Pint)
composer prepush    # Run all checks: phpstan, pint, test
```

Run a single test:
```bash
./vendor/bin/pest --filter "test name"
./vendor/bin/pest tests/Unit/TextGeneratorTest.php
```

## Architecture

### Core Service Pattern

`DenkService` is the main entry point, wrapping an OpenAI `Client` and exposing three generator factory methods:
- `text()` → `TextGenerator` (chat completions)
- `json()` → `JsonGenerator` (structured output with JSON schema)
- `image()` → `ImageGenerator` (DALL-E 3)

### Generator Hierarchy

All generators extend abstract `Generator` base class:

- **TextGenerator** and **JsonGenerator** share the `TextPrompts` trait for message handling
- **ImageGenerator** has its own `prompt()` method (no message history)
- All generators use fluent method chaining for configuration

### Message System

Three value objects implementing `Message` contract:
- `UserMessage` - User input
- `DeveloperMessage` - System/developer instructions (uses `developer` role, not deprecated `system`)
- `AssistantMessage` - Assistant responses

Messages are collected in `MessageCollection` and converted to OpenAI API format via `toArray()`.

### Laravel Integration

- `DenkServiceProvider` registers the service and publishes config
- `Denk` facade provides static access
- Config at `config/denk.php` with `OPENAI_API_KEY` env variable

### Testing

- Use `DenkFake` or individual generator fakes (`ImageGenerator::fake()`) for testing
- `spatie/invade` available for accessing protected properties in tests
- Tests use `CreateResponse::fake()` for mocking OpenAI responses

## Code Standards

- PHP 8.3+ with strict types
- PHPStan level 9
- Value objects use `readonly` properties
- Conventional commits style (lowercase: `feat:`, `fix:`, `chore:`)
