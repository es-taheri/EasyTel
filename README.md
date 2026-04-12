<h1 style="text-align: center">
    PHP Telegram Bot Library<br>
	<br>
    <img style="border-radius: 10px;" src="https://repository-images.githubusercontent.com/38116958/144ea600-fa7f-11e9-80c7-df2996317cee" alt="PHP Telegram Bot logo">
	<br>
</h1>

An easy to use php library for using [telegram bot api](https://core.telegram.org/bots/api)

[![API Version](https://img.shields.io/badge/Bot%20API-9.5%20%28October%202024%29-32a2da.svg)](https://core.telegram.org/bots/api-changelog#october-31-2024)
[![Join Telegram channel](https://img.shields.io/badge/telegram-@easytel-64659d.svg)](https://t.me/EasyTel_lib)

[![Latest Stable Version](https://img.shields.io/packagist/v/longman/telegram-bot.svg)](https://packagist.org/packages/estaheri/easytel)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D8.2-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/estaheri/easytel.svg)](https://github.com/es-taheri/easytel/LICENSE)
## Table of Contents
- [Introduction](#introduction)
- [Quick Usage](#quick-usage)
- [Instructions](#instructions)
    - [Require in project](#require-in-project)
- [Webhook installation](#webhook-installation)
    - [Self Signed Certificate](#self-signed-certificate)
    - [Unset Webhook](#unset-webhook)
- [Updates](#Updates)
- [Types](#types)
- [Methods](#methods)
## Introduction
This is a fully php classes structured and easy to use library for communicating with telegram bot api and managing your bots. 🤖\
As it's just a library you can use it next to any framework for example Laravel, Codeigniter,... 🌐\
Every Methods & Types has it's own description same as official telegram bot api document using standard phpdoc. 📄\
Unlike other library this library full support object oriented structured any Methods and Types available on [Telegram Bot API](https://core.telegram.org/bots/api) which means you can get full IDE auto complete/code suggestion when using library. (So feel free to use `Ctl+Space` 😉)\
All requests to telegram will be sent async through GuzzleHttp ⚡

## Quick Usage
* Require library in your project 📁

```
composer require estaheri/easytel
```

* Require composer autoload in your php code ⚓

```php
require_once __DIR__.'/vendor/autoload.php';
```

* Instantiating `Telegram` class📡

```php
use EasyTel\Telegram;
$tg = new Telegram($bot_token,$updates,$botapi_url,$request_method,$guzzle_client_options,$output);
#---------------------------------------------------------------------------------
$bot_token = '123456789:xxxxxxxxxxxxxxxx'; // Telegram bot token (You can get it from @botfather)
$updates = file_get_contents('php://input'); // (Optional) Pure telegram webhook updates you can use any method you want to getting updates
$botapi_url = 'https://api.telegram.org'; // (Optional) Your bot api url https://core.telegram.org/bots/api#using-a-local-bot-api-server (Mostly use telegram official server)
$request_method = 'POST'; // (Optional) Request method of http requests sends to telegram bot api url, Can be POST or GET
$guzzle_client_options = ['verify' => false]; // (Optional) Your custom guzzlehttp client options https://docs.guzzlephp.org/en/stable/request-options.html
$output = Telegram::OUTPUT_ARRAY; // (Optional) Output result of your requests send and received by telegram when using methods
```

* Now you can use telegram request methods or telegram webhook update✅
```php
use \EasyTel\Types\ReplyParameters;
# ------------------------- Methods -------------------------
$methods = $tg->methods; // Access to all telegram methods
$methods->sendMessage($chat_id, $text)
    ->parse_mode('markdown')
    ->reply_parameters(ReplyParameters::make($message_id, $chat_id));
# ------------------------- Updates -------------------------
$updates = $tg->updates; // Access to all webhook updates received
// IDE will suggest every property you see here because of full structured properties from their classes
$message = $updates->message;
$chat_id = $message->chat->id;
$message_id = $message->message_id;
$text = $message->text;
$reply_text = $message->reply_to_message->text;
```
## Instructions
Here we explained step by step how to use this library and working with telegram bots.
### Install composer
> [!NOTE]\
> If composer with php 8.2 installed on your system ignore this step

Linux based operating systems installation :
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'c8b085408188070d5f52bcfe4ecfbee5f727afa458b2573b8eaaf77b3419b0bf2768dc67c86944da1544f06fa544fd47') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
Windows based operating systems installation :\
> Download `Composer-Setup.exe` from link bellow than run it and follow installation steps.\
[https://getcomposer.org/Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe)

### Require in project
Open terminal in your project folder and run this composer command :
```bash
composer require estaheri/easytel
```
Than include composer `autoload.php` file to load EasyTel library in your codes :
```php
require_once __DIR__.'/vendor/autoload.php';
```
### Getting Updates
Now you have to use a way for getting your bot updates from telegram.\
Telegram gives us two options `getUpdates` method or `Webhook`.
* #### Webhook (Recommended)
  In this method you set a url to your webhook handler route or php file than telegram will send you any updates of messages or callback queries to it.\
  webhook.php :
    ```php
    $updates = file_get_contents('php://input');
    if(!empty($updates)) {
       $tg = new Telegram($bot_token, $updates);
       //...
    } else {
       http_response_code(400);
       echo 'Bad Request!';
    }
    ```
  Than set webhook to your webhook address :
  ```php
  $tg = new Telegram($bot_token);
  $tg->webhook->setWebhook('https://example.com/webhook.php');
  ```
* #### GetUpdates
  In this method you don't need to set webhook or webhook file just request to telegram for getting updates using getUpdates method :
  ```php
  $tg = new Telegram($bot_token);
  $tg->webhook->setWebhook('https://example.com/webhook.php');
  $result = $tg->methods->getUpdates()->_result();
  if ($result->success && $result->ok) {
     echo "Success";
  } else {
     $error = $result->ok ? $result->response()->description : $result->fail()->error;
     echo "Error: $error";
  }
  ```
