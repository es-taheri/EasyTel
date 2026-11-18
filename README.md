<h1 align="center">EasyTel</h1>

<p align="center">
    An easy to use, fully object oriented PHP library for the
    <a href="https://core.telegram.org/bots/api">Telegram Bot API</a>.<br>
    Every Bot API method and type exists as a real PHP class, generated from the
    official documentation — so your IDE already knows everything.
</p>

<p align="center">
    <a href="https://packagist.org/packages/estaheri/easytel"><img src="https://img.shields.io/packagist/v/estaheri/easytel.svg?label=latest" alt="Latest version"></a>
    <a href="https://packagist.org/packages/estaheri/easytel"><img src="https://img.shields.io/packagist/dt/estaheri/easytel.svg?label=downloads" alt="Downloads"></a>
    <a href="https://php.net/"><img src="https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg" alt="PHP >= 8.2"></a>
    <a href="https://core.telegram.org/bots/api"><img src="https://img.shields.io/badge/Bot%20API-10.4-32a2da.svg" alt="Bot API 10.4"></a>
    <a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="MIT license"></a>
    <a href="https://t.me/EasyTel_lib"><img src="https://img.shields.io/badge/telegram-@EasyTel__lib-64659d.svg" alt="Telegram channel"></a>
</p>

---

## Table of contents

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick start](#quick-start)
- [Sending requests](#sending-requests)
    - [Required and optional parameters](#required-and-optional-parameters)
    - [Fire and forget](#fire-and-forget)
    - [Handling results](#handling-results)
    - [Output formats](#output-formats)
- [Receiving updates](#receiving-updates)
    - [Webhook](#webhook)
    - [Reading an update](#reading-an-update)
    - [Long polling with getUpdates](#long-polling-with-getupdates)
- [Working with types](#working-with-types)
    - [Building objects](#building-objects)
    - [Arrays of objects](#arrays-of-objects)
    - [“One of” types (union bases)](#one-of-types-union-bases)
- [Sending files](#sending-files)
- [Cookbook](#cookbook)
    - [Inline keyboard](#inline-keyboard)
    - [Answering a callback query](#answering-a-callback-query)
    - [Chat permissions](#chat-permissions)
    - [Rich messages](#rich-messages)
- [Advanced](#advanced)
    - [Local Bot API server](#local-bot-api-server)
    - [Proxy and other Guzzle options](#proxy-and-other-guzzle-options)
    - [GET instead of POST](#get-instead-of-post)
- [Project structure](#project-structure)
- [Testing](#testing)
- [Notes on generated code](#notes-on-generated-code)
- [Contributing](#contributing)
- [License](#license)
- [Links](#links)

## Introduction

EasyTel is **just a library** — it does not ship a router, an update loop or a
framework. Drop it next to Laravel, Symfony, CodeIgniter, Slim, plain PHP, or
anything else.

What you get:

- **Complete coverage** — 193 method classes and 425 type classes, generated from
  the official Bot API documentation (currently Bot API **10.4**).
- **Full IDE support** — every method and type is a real class with typed
  properties and phpdoc copied from Telegram. `Ctrl+Space` works for method
  parameters, nested objects, enum-like string values and update fields.
- **Fluent method builders** — required parameters are constructor arguments,
  everything else is a chainable setter with typo protection
  (`BadMethodCallException` for unknown parameters).
- **Structured updates** — a webhook payload becomes a typed object graph:
  `$tg->updates->message->chat->id`, `$message->entities[0]->type`, …
- **Typed method results** — `_result()` gives you HTTP status, Telegram `ok`,
  the decoded body and error helpers.
- **Uploads included** — pass a `SplFileInfo`, a stream or a `CURLFile` and the
  request switches to `multipart/form-data` automatically.
- **No global state** — a single `Telegram` object owns its Guzzle client, token,
  base URL and output format.

## Requirements

| Requirement | Version |
|---|---|
| PHP | `>= 8.2` |
| [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle) | `^7.8` |
| [estaheri/json](https://packagist.org/packages/estaheri/json) | `^1.0` |
| `ext-json` | enabled (default) |

## Installation

```bash
composer require estaheri/easytel
```

Then load Composer's autoloader:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

## Quick start

```php
<?php

use EasyTel\Telegram;
use EasyTel\Types\InlineKeyboardButton;
use EasyTel\Types\InlineKeyboardMarkup;

require_once __DIR__ . '/vendor/autoload.php';

// The raw webhook body (or null when you instantiate the bot for a plain request)
$updates = file_get_contents('php://input') ?: null;

$tg = new Telegram(BOT_TOKEN, $updates);

// Incoming update
$message = $tg->updates?->message;
$chat_id = $message?->chat->id;

// Outgoing method
if ($message?->text === '/start') {
    $tg->methods->sendMessage('Welcome!')
        ->chat_id($chat_id)
        ->reply_markup(InlineKeyboardMarkup::make(
            inline_keyboard: [[
                InlineKeyboardButton::make(text: 'Open website', url: 'https://core.telegram.org/bots/api'),
            ]]
        ))
        ->_result();
}
```

### Constructor

```php
new Telegram(
    string  $bot_token,                     // token from @BotFather
    ?string $updates = null,                // raw webhook JSON (optional)
    string  $botapi_url = 'https://api.telegram.org',
    string  $request_method = 'POST',       // POST or GET
    array   $guzzle_client_options = [],    // proxy, verify, timeout, ...
    int     $output = Telegram::OUTPUT_OBJECT
);
```

| Argument | Purpose |
|---|---|
| `$bot_token` | Bot token. Used to build `https://api.telegram.org/bot<token>/` |
| `$updates` | Raw JSON body from the webhook. Parsed into `EasyTel\Types\Update` |
| `$botapi_url` | Point this at a [local Bot API server](#local-bot-api-server) if you use one |
| `$request_method` | `POST` (recommended) or `GET`; `GET` sends parameters in the query string |
| `$guzzle_client_options` | Any [Guzzle request option](https://docs.guzzlephp.org/en/stable/request-options.html): `proxy`, `verify`, `timeout`, `connect_timeout`, … |
| `$output` | Default format for `Result::response()` and `Types` output |

The public properties are:

```php
$tg->methods;   // EasyTel\Helper\Methods  — all Bot API methods
$tg->updates;   // EasyTel\Types\Update|array|null — parsed webhook payload
$tg->webhook;   // EasyTel\Webhook — legacy helper: setWebhook/deleteWebhook/getWebhookInfo
$tg->output;    // int — the configured output format
```

## Sending requests

Every Bot API method is available on `$tg->methods` with the same name as in the
official documentation (camelCase):

```php
$tg->methods->sendMessage(...);
$tg->methods->sendPhoto(...);
$tg->methods->banChatMember(...);
$tg->methods->getChatAdministrators(...);
```

### Required and optional parameters

Parameters marked **required** in the Bot API documentation are constructor
arguments; all optional ones are chainable setters. The setter name is exactly
the Bot API parameter name, so the official docs apply one to one.

```php
use EasyTel\Types\ReplyParameters;

$tg->methods->sendMessage('Hello from EasyTel')  // required: text
    ->chat_id($chat_id)                          // optional fluent setters
    ->message_thread_id($topic_id)
    ->parse_mode('HTML')
    ->disable_notification(true)
    ->reply_parameters(ReplyParameters::make(
        message_id: $message_id,
        chat_id: $chat_id,
    ))
    ->_result();
```

A setter that does not exist for the method throws
`BadMethodCallException`, so typos never reach Telegram:

```php
$tg->methods->sendMessage('hi')->parse_mod('HTML'); // BadMethodCallException
$tg->methods->sendMessage('hi')->chat_id();         // InvalidArgumentException
```

### Fire and forget

`_result()` is optional. If you ignore the builder, the request is sent when the
object is destroyed:

```php
// Both send a message, the second one simply ignores the response.
$tg->methods->sendMessage('done')->chat_id($chat_id)->_result();
$tg->methods->sendMessage('done')->chat_id($chat_id);
```

Use `_result()` whenever you need to know whether the request worked.

### Handling results

`_result()` returns an `EasyTel\Handler\Result`:

```php
use EasyTel\Helper\Types;

$result = $tg->methods->getMe()->_result();

if ($result->success && $result->ok) {
    // HTTP 2xx AND Telegram "ok": true
    $me = Types::make($result->response())->User();
    echo "Logged in as @{$me->username}";
} else {
    $error = $result->success
        ? $result->response()->description   // Telegram error (400, 403, 409, 429, ...)
        : $result->fail()->error;            // transport error (DNS, timeout, TLS, ...)

    echo "Error: $error";
}
```

| Member | Meaning |
|---|---|
| `$result->success` | The HTTP request itself succeeded |
| `$result->ok` | The decoded body has `"ok": true` |
| `$result->response(?int $format = null)` | The full Telegram envelope (`{"ok":…,"result":…}`) |
| `$result->success()` | `{code, header, size}` or `null` |
| `$result->fail()` | `{code, error}` for transport errors, or `null` |

> The bot token is never exposed in transport errors: messages are sanitized to
> `https://api.telegram.org/bot<token>/…`, so they are safe to log.

### Output formats

`Result::response()` and every `_output()` call accept one of:

```php
Telegram::OUTPUT_OBJECT;  // stdClass (default)
Telegram::OUTPUT_ARRAY;   // nested arrays
Telegram::OUTPUT_JSON;    // JSON string
```

```php
$tg = new Telegram(BOT_TOKEN, $updates, output: Telegram::OUTPUT_ARRAY);

$result = $tg->methods->getMe()->_result();
$id = $result->response()['result']['id'];   // array, because OUTPUT_ARRAY was set

// Or ask for another format just for this call:
$json = $result->response(Telegram::OUTPUT_JSON);
```

A body that is not JSON (for example a gateway HTML error page) is returned
as-is instead of being silently converted to `false`.

## Receiving updates

Telegram offers two ways to receive updates: a **webhook** (recommended) or
**long polling**.

### Webhook

Point Telegram at a public HTTPS endpoint that runs your handler:

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

$body = file_get_contents('php://input');

// Reject anything that is not your bot's webhook traffic.
if ($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] !== WEBHOOK_SECRET) {
    http_response_code(403);
    exit;
}

$tg = new EasyTel\Telegram(BOT_TOKEN, $body);
$tg->updates?->message?->text; // typed update graph
```

Register the webhook:

```php
$tg = new EasyTel\Telegram(BOT_TOKEN);

$tg->methods->setWebhook('https://example.com/telegram.php')
    ->secret_token(WEBHOOK_SECRET)                 // sent as X-Telegram-Bot-Api-Secret-Token
    ->max_connections(40)
    ->drop_pending_updates(true)
    ->_result();
```

Inspect or remove it:

```php
$info = $tg->methods->getWebhookInfo()->_result();
$info = EasyTel\Helper\Types::make($info->response())->WebhookInfo();

$tg->methods->deleteWebhook()->drop_pending_updates(true)->_result();
```

`$tg->webhook->setWebhook(...)`, `deleteWebhook(...)` and `getWebhookInfo()` are
kept as convenience aliases for the same three methods.

### Reading an update

`$tg->updates` is a fully typed object graph. Only the fields present in the
payload are initialized, so use `isset()` or `?->`:

```php
$update  = $tg->updates;                       // EasyTel\Types\Update
$message = $update?->message;                  // EasyTel\Types\Message

$update_id = $update?->update_id;
$chat_id   = $message?->chat->id;
$user_id   = $message?->from->id;
$text      = $message?->text;
$username  = $message?->from->username ?? $message?->from->first_name;
$timestamp = $message?->date;

// reply / entities
$replied_text = $message?->reply_to_message->text;
$entity       = $message?->entities[0] ?? null;   // EasyTel\Types\MessageEntity
$entity_type  = $entity?->type;                   // "bold", "mention", ...

// media
$photo   = $message?->photo;                      // array of EasyTel\Types\PhotoSize
$file_id = $photo[0]->file_id ?? null;

// callback queries, inline queries, chat member changes, ...
$callback    = $update?->callback_query;
$callback_id = $callback?->id;
$data        = $callback?->data;
```

`getUpdates`, `getChatAdministrators`, `getMyCommands` and other list-returning
methods resolve to arrays of typed objects:

```php
$users = Types::make($result->response())->ChatMember();  // EasyTel\Types\ChatMember[]
```

### Long polling with getUpdates

```php
$tg = new EasyTel\Telegram(BOT_TOKEN);
$tg->methods->deleteWebhook()->_result();     // polling requires no webhook

$offset = 0;

while (true) {
    $result = $tg->methods->getUpdates()
        ->offset($offset)
        ->limit(100)
        ->timeout(30)                          // long polling
        ->allowed_updates(json_encode(['message', 'callback_query']))
        ->_result();

    if (!$result->success || !$result->ok) {
        sleep(3);
        continue;
    }

    /** @var EasyTel\Types\Update[] $updates */
    $updates = EasyTel\Helper\Types::make($result->response())->Update();

    foreach ($updates as $update) {
        $offset = $update->update_id + 1;
        echo $update->message?->text ?? '', PHP_EOL;
    }
}
```

## Working with types

All Bot API objects are classes under `EasyTel\Types`. They are plain data
objects: public typed properties, a `make()` factory and chainable setters.

### Building objects

```php
use EasyTel\Types\ReplyParameters;

// named arguments (recommended)
$reply = ReplyParameters::make(
    message_id: 42,
    chat_id: $chat_id,
    allow_sending_without_reply: true,
);

// fluent setters
$reply = ReplyParameters::make()->message_id(42)->chat_id($chat_id);

// create from raw Telegram JSON (or from an array)
$user = EasyTel\Helper\Types::make($result->response())->User();
```

`EasyTel\Helper\Types` is the inbound factory: `Types::make($payload)->User()`,
`->Message()`, `->Chat()`, … It unwraps the `{"ok":true,"result":…}` envelope
automatically and supports all 425 types (with `@method` annotations for
autocomplete).

### Arrays of objects

Array fields are hydrated into arrays of typed objects, including nested arrays
(such as keyboard rows):

```php
$message->entities[0]                         // EasyTel\Types\MessageEntity
$message->photo[0]                            // EasyTel\Types\PhotoSize
$message->reply_markup->inline_keyboard[0][0]  // EasyTel\Types\InlineKeyboardButton
$message->new_chat_members[0]                 // EasyTel\Types\User

// getChat() returns the full chat info
$chat = EasyTel\Helper\Types::make($tg->methods->getChat($chat_id)->_result()->response())->ChatFullInfo();
$chat->available_reactions[0]                 // EasyTel\Types\ReactionTypeEmoji
```

### “One of” types (union bases)

Some fields can be one of several objects, e.g. `forward_origin` is a
`MessageOrigin` (`User`, `HiddenUser`, `Chat` or `Channel`). These are generated
as a base class with one property per variant; the matching variant is a nested
property:

```php
$origin  = $tg->updates->message->forward_origin;   // EasyTel\Types\MessageOrigin
$channel = $origin->messageoriginchannel;           // EasyTel\Types\MessageOriginChannel
$title   = $channel->chat->title;
```

Other examples: `TransactionPartner` (`$tx->source->transactionpartneruser`),
`ChatMember` (`$update->my_chat_member->new_chat_member->chatmemberadministrator`),
`BackgroundType`, `ReactionType`, `StoryAreaType`, `WidgetQueryType`.

When a field is a real PHP union (for example `InputRichMessageMedia::$media`),
the concrete class is chosen from the payload's `type`. `RichText` — documented
by Telegram as “a String for plain text, an Array of RichText, or one of the
following types” — accepts all three forms.

## Sending files

Pass a `SplFileInfo`, an open stream or a `CURLFile` and the library switches the
request to `multipart/form-data`:

```php
// upload from disk
$tg->methods->sendPhoto(new \SplFileInfo('/path/to/photo.jpg'))
    ->chat_id($chat_id)
    ->caption('Uploaded with EasyTel')
    ->_result();

// upload from an open stream
$tg->methods->sendDocument(fopen('/path/to/file.pdf', 'rb'))
    ->chat_id($chat_id);

// re-send a file that already exists on Telegram's servers
$tg->methods->sendPhoto($file_id)->chat_id($chat_id);
```

Plain strings are **never** treated as local paths — they are forwarded to
Telegram as a `file_id` or an HTTP URL, exactly as the Bot API expects.

## Cookbook

### Inline keyboard

```php
use EasyTel\Types\InlineKeyboardButton;
use EasyTel\Types\InlineKeyboardMarkup;

$keyboard = InlineKeyboardMarkup::make(inline_keyboard: [
    [
        InlineKeyboardButton::make(text: 'Yes', callback_data: 'answer:yes'),
        InlineKeyboardButton::make(text: 'No', callback_data: 'answer:no'),
    ],
    [
        InlineKeyboardButton::make(text: 'Documentation', url: 'https://core.telegram.org/bots/api'),
    ],
]);

$tg->methods->sendMessage('Continue?')
    ->chat_id($chat_id)
    ->reply_markup($keyboard)
    ->_result();
```

### Answering a callback query

```php
$callback = $tg->updates?->callback_query;

if ($callback !== null) {
    $tg->methods->answerCallbackQuery($callback->id)
        ->text('Saved!')
        ->show_alert(true)     // or false for a toast
        ->_result();

    // the message the button belongs to
    $message_id = $callback->message?->message_id;
}
```

### Chat permissions

```php
use EasyTel\Types\ChatPermissions;

$tg->methods->restrictChatMember(
    $chat_id,
    $user_id,
    ChatPermissions::make(can_send_messages: false)
)->until_date(time() + 3600);
```

### Rich messages

```php
use EasyTel\Types\InputRichBlockParagraph;
use EasyTel\Types\InputRichMessage;
use EasyTel\Types\RichText;
use EasyTel\Types\RichTextBold;

$tg->methods->sendRichMessage(
    InputRichMessage::make(blocks: [
        InputRichBlockParagraph::make(type: 'paragraph', text: 'Plain text works too'),
        InputRichBlockParagraph::make(
            type: 'paragraph',
            text: RichText::make(
                richtextbold: RichTextBold::make(type: 'bold', text: 'and nested formatting')
            )
        ),
    ])
)->chat_id($chat_id)->_result();
```

## Advanced

### Local Bot API server

```php
$tg = new EasyTel\Telegram(
    BOT_TOKEN,
    $updates,
    botapi_url: 'http://localhost:8081'
);
```

The request URL becomes `http://localhost:8081/bot<token>/<method>`.

### Proxy and other Guzzle options

```php
$tg = new EasyTel\Telegram(BOT_TOKEN, $updates, guzzle_client_options: [
    'proxy'  => 'socks5://127.0.0.1:10808',
    'verify' => false,          // do not do this in production
    'timeout' => 30,
]);
```

### GET instead of POST

```php
$tg = new EasyTel\Telegram(BOT_TOKEN, $updates, request_method: 'GET');
```

With `GET`, parameters are sent in the query string. `POST` (the default) is
recommended because Telegram supports larger payloads that way and uploads
require `multipart/form-data`.

## Project structure

```
src/
├── Telegram.php          entry point: token, Guzzle client, updates, methods
├── Webhook.php           setWebhook / deleteWebhook / getWebhookInfo helper
├── Handler/
│   ├── Request.php       transport: form / query / multipart + error mapping
│   └── Result.php        success, ok, response(), fail()
├── Helper/
│   ├── Methods.php       facade listing all 193 methods
│   ├── Types.php         inbound factory: Types::make($json)->User()
│   └── Statics.php       hydration and output formatting
├── Methods/              193 fluent request builders
└── Types/                425 data objects
test/
├── test.php              webhook handler example
├── test2.php             getMe + sendMessage smoke test
└── config.php            reads credentials from the environment
```

## Testing

The bundled scripts read credentials from environment variables — nothing is
hard-coded:

```bash
export EASYTEL_BOT_TOKEN="123456789:AA..."
export EASYTEL_TEST_USER_ID="123456789"      # your own user id
export EASYTEL_PROXY="socks5://127.0.0.1:10808"   # optional

php test/test2.php
```

`test/test2.php` calls `getMe`, hydrates the returned `User` object and (when
`EASYTEL_TEST_USER_ID` is set) sends a message to you. `test/test.php` is a
complete webhook handler showing keyboards, replies, callback queries and photo
forwarding.

## Notes on generated code

`src/Types` and `src/Methods` are generated from the official Bot API
documentation (including the phpdoc text and the required/optional split). They
are kept in sync with the documentation; **do not edit them by hand** — put
changes in the generator templates so a regeneration does not overwrite them.

Shared behaviour lives in the hand-written classes — `Telegram.php`,
`Handler/Request.php` (transport), `Handler/Result.php` (response),
`Helper/Statics.php` (hydration/output) and `Helper/Types.php` (inbound factory).

## Contributing

1. Fork the repository and create a branch.
2. Keep the code style of the surrounding files (PSR-12, typed properties, no
   `strict_types` currently).
3. Run `php -l` on changed files and `php test/test2.php` with your own bot.
4. Open a pull request describing the change and, for API updates, the Bot API
   changelog entry it corresponds to.

Bug reports and feature requests are welcome in the
[issue tracker](https://github.com/es-taheri/EasyTel/issues).

## License

EasyTel is released under the [MIT license](LICENSE).

Copyright (c) 2023 Esmaeil Taheri.

## Links

- [Telegram Bot API documentation](https://core.telegram.org/bots/api)
- [Bot API changelog](https://core.telegram.org/bots/api-changelog)
- [Packagist package](https://packagist.org/packages/estaheri/easytel)
- [GitHub repository](https://github.com/es-taheri/EasyTel)
- [Telegram channel](https://t.me/EasyTel_lib)
- [Getting a bot token from @BotFather](https://core.telegram.org/bots/features#botfather)
