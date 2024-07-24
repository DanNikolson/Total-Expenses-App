<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This class is responsible for handling incoming HTTP requests and extracting the referer
 * from the request headers.
 *
 */
class RequestService
{
    /**
     * @param SessionInterface $session The session interface used to retrieve the previous URL
     */
    public function __construct(private readonly SessionInterface $session)
    {
    }
    /**
     * Gets the referer from the request headers. If the referer is not present in the headers,
     * it retrieves it from the session.
     *
     * If the referer is present in the headers but it doesn't belong to the same host as the
     * current request, it retrieves the previous URL from the session.
     *
     * @param ServerRequestInterface $request The request object
     * @return string The referer URL
     */
    public function getReferer(ServerRequestInterface $request): string
    {
        $referer = $request->getHeader('referer')[0] ?? '';

        if (!$referer) {
            return $this->session->get('previousUrl');
        }

        $refererHost = parse_url($referer, PHP_URL_HOST);

        if (!$refererHost !== $request->getUri()->getHost()) {
            $referer = $this->session->get('previousUrl');
        }

        return $referer;
    }
}
