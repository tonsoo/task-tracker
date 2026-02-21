<?php

namespace Tonso\TaskTracker\Messaging\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface MessagingDriver
{
    public function verify(Request $request): Response;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function parse(Request $request): array;
}
