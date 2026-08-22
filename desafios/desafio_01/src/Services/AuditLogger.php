<?php

declare(strict_types=1);

namespace Desafio01\Services;

class AuditLogger {
    private static array $logs = [];

    public static function log(string $event): void {
        self::$logs[] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
        ];
    }

    public static function getLogs(): array {
        return self::$logs;
    }

    public static function getLogCount(): int {
        return count(self::$logs);
    }

    public static function reset(): void {
        self::$logs = [];
    }
}
