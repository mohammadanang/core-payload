<?php

namespace Apollo16\Core\Payload\Middleware;

use Apollo16\Core\Payload\Broker;
use Closure;

/**
 * Payload Middleware.
 *
 * @author      mohammad.anang  <m.anangnur@gmail.com>
 */

class CatchPayloadRequest
{
    /**
     * Payload broker.
     *
     * @var \Apollo16\Core\Payload\Broker
     */
    protected $payloadBroker;

    /**
     * Create new middleware.
     *
     * @param \Apollo16\Core\Payload\Broker $broker
     */
    public function __construct(Broker $broker)
    {
        $this->payloadBroker = $broker;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if this request has payload on its body
        if($request->has('payload')) {
            $this->payloadBroker->createFromInput($request->input('payload'));
        }

        return $next($request);
    }
}