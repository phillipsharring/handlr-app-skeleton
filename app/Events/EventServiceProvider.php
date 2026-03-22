<?php

declare(strict_types=1);

namespace App\Events;

use Handlr\Core\Container\ContainerInterface;
use Handlr\Core\EventManager;

// Auth listeners
use App\Auth\Login\Listeners\UpdateLastLoginListener;

class EventServiceProvider
{
    public static function register(ContainerInterface $container, EventManager $eventManager): void
    {
        // ── Login ──
        $eventManager->register('user.logged_in', $container->get(UpdateLastLoginListener::class));
    }
}
