<?php

namespace App\Resolvers;

use OwenIt\Auditing\Contracts\Resolver;

class CustomUrlResolver implements Resolver
{
    /**
     * Resolve the URL.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return string|null
     */
    public static function resolve($model)
    {
        if (app()->runningInConsole()) {
            return 'console';
        }

        $url = request()->fullUrl();
        $requestId = app('request-id');

        if ($requestId) {
            $url .= " [Request ID: {$requestId}]";
        }

        return $url;
    }
}
