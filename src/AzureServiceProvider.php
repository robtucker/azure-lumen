<?php

namespace Azure;

use Illuminate\Support\ServiceProvider;

class AzureServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        $this->app->bind('azure.storage', function() {
            return new MailClient(new OfficeAuthorization());
        });

    }
}
