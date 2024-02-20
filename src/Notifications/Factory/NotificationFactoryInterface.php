<?php

namespace App\Notifications\Factory;

use Symfony\Component\Notifier\Notification\Notification;

interface NotificationFactoryInterface
{
    public function createNotification(string $subject): Notification;
    public static function getIndex(): string;
}