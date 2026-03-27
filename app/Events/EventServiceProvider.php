<?php

declare(strict_types=1);

namespace App\Events;

use Handlr\Core\Container\ContainerInterface;
use Handlr\Core\EventManager;

// Auth listeners
use App\Auth\EmailVerification\SendVerificationEmailListener;
use App\Auth\Login\Listeners\UpdateLastLoginListener;

class EventServiceProvider
{
    public static function register(ContainerInterface $container, EventManager $eventManager): void
    {
        // ── Signup ──
        $eventManager->register('user.signed_up', $container->get(SendVerificationEmailListener::class));

        // ── Login ──
        $eventManager->register('user.logged_in', $container->get(UpdateLastLoginListener::class));
    }
}
