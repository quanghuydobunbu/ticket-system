<?php

namespace App\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match($this) {
            self::Draft => 'Nháp',
            self::Published => 'Đã xuất bản',
            self::Cancelled => 'Đã hủy',
            self::Completed => 'Đã hoàn thành',
        };
    }
}
