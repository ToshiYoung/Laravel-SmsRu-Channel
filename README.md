# Sms.ru notification channel for Laravel

## Install
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
