<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Response\ApiResponse;
use App\Models\Config;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppVersion
{
    public function __construct(
        private readonly Config $config,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appVersion = $request->headers->get('APP_VERSION');
        if ($appVersion === null) {
            return $next($request);
        }
        $minVersion = $this->config->where('name', Config::MIN_APP_VERSION)->first();
        $versionCompared = version_compare($appVersion, $minVersion->value);
        if ($versionCompared < 0) {
            return ApiResponse::bad('app version bellow minimum, please update your app');
        }
        return $next($request);
    }
}
