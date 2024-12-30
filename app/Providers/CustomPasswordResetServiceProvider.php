<?php

namespace App\Providers;

use Illuminate\Auth\Passwords\PasswordResetServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Passwords\PasswordBrokerManager;

class CustomPasswordResetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new class($app) extends PasswordBrokerManager {
                protected function getTable()
                {
                    return 'password_reset_tokens';
                }

                protected function getUserColumn()
                {
                    return 'Email';
                }
            };
        });
    }
}

