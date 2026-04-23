<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * ✅ Laravel 11/12/13: استخدم '*' إذا كنت خلف Load Balancer أو Reverse Proxy
     * (مثل: Nginx, Apache, Cloudflare, AWS, DigitalOcean...)
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * ✅ Laravel 11+: HEADER_X_FORWARDED_ALL محذوف من Symfony 6+
     * استخدم الثوابت الصريحة (Bitwise) باش تكون متوافق مع PHP 8.3+
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO;
}
