<?php

declare(strict_types=1);

namespace App\Auth\Logout;

use Handlr\Handlers\Handler;
use Handlr\Handlers\HandlerInput;
use Handlr\Handlers\HandlerResult;
use Handlr\Session\SessionInterface;

class LogoutHandler implements Handler
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly HandlerResult $result
    ) {}

    public function handle(HandlerInput|array $input = []): HandlerResult
    {
        $this->session->regenerate();
        $this->session->destroy();

        return $this->result->ok();
    }
}
