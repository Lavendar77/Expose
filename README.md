# Exposé
Guide for my Laravel Applications.

## Working with Notifications
From the official Laravel documentation, notifications should be short, informational messages that notify users of something that occurred in your application. For example, if you are writing a billing application, you might send an "Invoice Paid" notification to your users via the email and SMS channels.

### Creating Notifications
`php artisan make:notification UserRegistered`

**Note:** By default, the `User` model already uses the `notify` method of the `Notifiable` trait or using the `Notification` facade

### Configure the Notification
**Note:** Notifications are stored in `app/Notifications`

```php
public function toMail($notifiable)
{
    return (new MailMessage)
                ->line('Welcome to Expose')
                ->action('Continue on Site', url('/'))
                ->line('Thank you for registering to our application!');
}
```
*The above method takes care of the notification through the `mail` channel.*

### Test
If you are using the `toMail` method, make sure your mail server is configured in the `.env` file.

#### In your controller
In this example, our notification is to be triggered when a user registers, hence, we will stick to the default `app/Http/Controllers/Auth/RegisterController.php` file.
```php
use App\Notifications\UserRegistered;
.
..
...
protected function create(array $data)
{
    $user = new User();
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->password = Hash::make($data['password']);
    $user->save();

    $user->notify(new UserRegistered());

    return $user;
}
```
#### Preview in Browser
```php
Route::get('mail', function () {
    return (new App\Notifications\UserRegistered())
                ->toMail(App\Models\User::find(1));
});
```
![Default look](https://github.com/Lavendar77/Expose/tree/notifications/public/images/notifications/default.png)

### Other Methods
1. `greeting(string $message)`
	Add a greeting to the notification.
	![Greeting](https://github.com/Lavendar77/Expose/tree/notifications/public/images/notifications/greeting.png)

2. `error()`
	Some notifications inform users of errors, such as a failed invoice payment. You may indicate that a mail message is regarding an error by calling the error method when building your message. When using the error method on a mail message, the call to action button will be red instead of blue.
	![Error](https://github.com/Lavendar77/Expose/tree/notifications/public/images/notifications/error.png)

3. `from(email $email, string $name)`
	Customizing the sender of the mail notification
	![Customer Sender](https://github.com/Lavendar77/Expose/tree/notifications/public/images/notifications/from.png)

4. `subject(string $message)`
	Add the subject of the mail
	![Subject](https://github.com/Lavendar77/Expose/tree/notifications/public/images/notifications/subject.png)
