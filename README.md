# Sms.ru notification channel for Laravel

## Install
```
composer require toshiyoung/laravel-smsru-channel
```

```
php artisan vendor:publish --provider="TY\SmsRu\SmsRuServiceProvider"
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use TY\SmsRu\Messages\SmscRuMessage;
use TY\SmsRu\Channels\SmscRuChannel;

class SmsNotify extends Notification
{
    public function via($notifiable)
    {
        return [SmsRuChannel::class];
    }

    public function toSmsRu($notifiable)
    {
        return new SmsRuMessage("Привет! Это тестовое СМС.");
    }
}
```

```php
Notification::route('sms_ru', '79109876543')->notify(new SmsNotify());
```

## Testing

``` bash
$ composer test
```
