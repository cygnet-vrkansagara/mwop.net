<?php
namespace Mwop;

class BodyParams
{
    private $nonBodyRequests = [
        'GET',
        'HEAD',
        'OPTIONS',
    ];

    public function __invoke($request, $response, $next)
    {
        if (in_array($request->getMethod(), $this->nonBodyRequests)) {
            return $next($request, $response);
        }

        $header     = $request->getHeader('Content-Type');
        $priorities = [
            'form'     => 'application/x-www-form-urlencoded',
            'json'     => '[/+]json',
        ];

        $matched = false;
        foreach ($priorities as $type => $pattern) {
            $pattern = sprintf('#%s#', $pattern);
            if (! preg_match($pattern, $header)) {
                continue;
            }
            $matched = $type;
            break;
        }

        switch ($matched) {
            case 'form':
                // $_POST is injected by default into the request body parameters;
                // re-inject them into the attributes.
                return $next($request->withAttribute('body', $request->getBodyParams()), $response);
            case 'json':
                $rawBody = $request->getBody()->getContents();
                return $next(
                    $request
                    ->withAttribute('rawBody', $rawBody)
                    ->withAttribute('body', json_decode($rawBody, true)),
                    $response
                );
            default:
                break;
        }

        return $next($request, $response);
    }
}
