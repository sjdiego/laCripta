<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\{Request, Response};

class HomeController
{
    public function index(): Response
    {
        return new Response(sprintf('<html><body><pre>%s</pre></body></html>', 'Hello world!'));
    }

    public function favicon(): Response
    {
        return new Response();
    }
}
