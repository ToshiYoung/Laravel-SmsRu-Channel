# Sms.ru notification channel for Laravel

## Install
Add to composer.json
```
"require": {
    ...,
    "toshiyoung/laravel-smsru-channel": "1.0"
},
"repositories": [
    {
        "type": "package",
        "package": {
            "name": "toshiyoung/laravel-smsru-channel",
            "version": "1.0",
            "source": {
                "url": "https://github.com/ToshiYoung/Laravel-SmsRu-Channel.git",
                "type": "git",
                "reference": "v1.0.0"
            }
        }
    }
],
```
After Run
```
composer update --prefer-source
```
And publish SmsRuServiceProvider
```
php artisan vendor:publish --provider="LaravelSmsRu\SmsRuServiceProvider"
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use LaravelSmsRu\Messages\SmscRuMessage;
use LaravelSmsRu\Channels\SmscRuChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [SmsRuChannel::class];
    }

    public function toSmsRu($notifiable)
    {
        return new SmsRuMessage("Task #{$notifiable->id} is complete!");
    }
}
```

## Testing

``` bash
$ composer test
```
