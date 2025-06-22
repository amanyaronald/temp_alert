<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Sensy\Scrud\app\Http\Helpers\Model;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::get('sms/webhook', function () {

    $req = request();

    $req->merge([
        "SmsStatus" => 'success',
        "SmsSid" => Str::random(10),
        "SmsMessageSid" => Str::random(10),
        "MessageSid" => Str::random(10),
        "From" => '256783940334',
        "Body" => $req->Body,
    ]);

    return Model::call(request(), 'Sms', 'webhook');
})->name('smsWebhook');


##FOR TESTING ONLY
Route::get('sendSms', function () {

    $req = request();
    $req->merge([
        "to" => env('SENSOR_PHONE_NO'),
        "message" => 'sensor=1;temp=25.6;type=alert;status=normal',
    ]);

    return Model::call(request(), 'Sms', 'sendMessage');
});
##FOR TESTING ONLY
require __DIR__ . '/auth.php';
