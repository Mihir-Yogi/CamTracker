<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\TrustProxies as Middleware;
use Symfony\Component\HttpFoundation\Response;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers;

    public function __construct()
    {
        $this->proxies = '*'; // Trust all proxies; change this if you have specific proxies
        $this->headers = Request::HEADER_X_FORWARDED_ALL; // You can adjust this as needed
    }
}
