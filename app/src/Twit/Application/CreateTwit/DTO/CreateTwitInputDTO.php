<?php

declare(strict_types=1);

namespace App\Twit\Application\CreateTwit\DTO;

use DateTime;
use Exception;

class CreateTwitInputDTO
{
    private const VALUES = [
        'user_id',
        'content',
        'created_at',
    ];

    private function __construct(public readonly int $user_id, public readonly string $content, public readonly DateTime $created_at)
    {
    }

    /**
     * @throws Exception
     */
    public static function create(?int $user_id, ?string $content, ?DateTime $created_at): self
    {
        if (!$created_at) {
            $created_at = new DateTime('now');
        }

        static::validateFields(\func_get_args());

        return new static($user_id, $content, $created_at);
    }

    /**
     * @throws Exception
     */
    private static function validateFields(array $fields): void
    {
        $values = \array_combine(self::VALUES, $fields);

        $emptyValues = [];
        foreach ($values as $key => $value) {
            if (\is_null($value)) {
                $emptyValues[] = $key;
            }
        }

        if (!empty($emptyValues)) {
            throw new Exception('Some empty values: '.print_r($emptyValues, true));
            // throw InvalidArgumentException::createFromArray($emptyValues);
        }
    }
}
