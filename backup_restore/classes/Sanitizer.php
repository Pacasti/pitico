<?php

declare(strict_types=1);

namespace database;

/**
 * Sanitizer class
 *
 * @see https://www.php.net/manual/en/filter.filters.sanitize.php
 */
class Sanitizer
{
    /**
     * @param  array<string, string> $params
     *
     * @return array<string, string>
     */
    public static function sanitize(array $params): array
    {
        // Sanitize and validate user input
        $email = filter_var($params['email'], FILTER_VALIDATE_EMAIL);

        /**
         * @var array<string, string> $input
         **/
        $input = [
            'email'         => $email,
            'phone_number'  => htmlspecialchars($params['phone_number']),
            'last_name'     => htmlspecialchars($params['last_name']),
            'first_name'    => htmlspecialchars($params['first_name']),
            'company'       => htmlspecialchars($params['company']),
            'message'       => htmlspecialchars($params['message']),
        ];

        // remove spaces
        return array_map('trim', $input);
    }

}
