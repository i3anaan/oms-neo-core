<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Collection;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data, $meta = null, $message = null) {
            if ($data instanceof Collection && $data->count() == 1) {
                $data = $data[0];
            } else if (is_array($data) && count($data) == 1) {
                $data = $data[0];
            }

            $response = array(
                'success'   => true,
                'meta'      => $meta,
                'data'      => $data,
                'message'   => $message
            );
            return response()->json($response);
        });
        Response::macro('failure', function ($message = 'Request failed', $code = 400) {
            $response = array(
                'success'   => false,
                'message'   => $message
            );
            return response()->json($response, $code);
        });

        Response::macro('forbidden', function () {
            return response()->failure('Forbidden', 403);
        });
        Response::macro('unauthorized', function () {
            //TODO add WWW-Authenticate header.
            return response()->failure('Unauthorized', 401);
        });
        Response::macro('credentialsFailure', function () {
            //TODO add WWW-Authenticate header.
            return response()->failure('Invalid login credentials.', 400);
        });
        Response::macro('notImplemented', function () {
            return response()->failure('Not Implemented', 501);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
