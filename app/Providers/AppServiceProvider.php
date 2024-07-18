<?php

namespace App\Providers;

use App\Http\Controllers\ProjectController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading();

        // create a super admin
        Gate::before(function (User $user) {
            if ($user->id == 1) {
                return true;
            }
        });

        Gate::define('create-project', [ProjectController::class, 'create']);

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Welcome to customer portal, please verify your email address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });

        Carbon::macro('inApplicationTimezone', function() {
            return $this->tz(config('app.timezone_display'));
        });

        Carbon::macro('inUserTimezone', function() {
            return $this->tz(auth()->user()?->timezone ?? config('app.timezone_display'));
        });
    }
}
