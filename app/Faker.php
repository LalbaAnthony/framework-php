<?php

namespace App;

use Exception;

/**
 * Class Faker
 *
 * This class is used to generate fake data for testing purposes.
 */
class Faker
{
    public const DATA = [
        'email' => [
            'domains' => ['example.com', 'test.com', 'demo.com', 'sample.com'],
        ],
        'passwords' => [
            'simple' => 'password',
            'complex' => 'P@ssw0rd!23',
            'long' => 'ThisIsAVeryLongPassword12345',
        ],
        'first_names' => ['John', 'Jane', 'Alice', 'Bob', 'Charlie', 'Diana', 'Eve', 'Frank'],
        'last_names' => ['Doe', 'Smith', 'Johnson', 'Brown', 'Davis', 'Miller', 'Wilson', 'Moore'],
    ];

    /**
     * Generates a fake email address.
     *
     * @return string
     */
    public static function email(): string
    {
        $firstNames = self::DATA['first_names'];
        $lastNames = self::DATA['last_names'];
        $domains = self::DATA['email']['domains'];

        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $domain = $domains[array_rand($domains)];

        $email = strtolower($firstName . '.' . $lastName . '@' . $domain);

        return $email;
    }

    /**
     * Generates a fake name.
     *
     * @return string
     */
    public static function fullname(): string
    {
        $firstNames = self::DATA['first_names'];
        $lastNames = self::DATA['last_names'];

        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];

        return $firstName . ' ' . $lastName;
    }

    /**
     * Generates a fake password.
     *
     * @param string $type The type of password to generate ('simple', 'complex', 'long').
     * @return string
     */
    public static function password(string $type): string
    {
        return self::DATA['passwords'][$type] ?? '';
    }

    /**
     * Generates a fake date.
     *
     * @param string $format The date format.
     * @param int $start The start year.
     * @param int $end The end year.
     * @return string
     */
    public static function date(string $format = 'Y-m-d', int $start = 1970, int $end = 2023): string
    {
        $timestamp = mt_rand(strtotime("$start-01-01"), strtotime("$end-12-31"));
        return date($format, $timestamp);
    }

    /**
     * Generates a fake phone number.
     *
     * @return string
     */
    public static function phone(): string
    {
        $number = '+1'; // Country code
        for ($i = 0; $i < 10; $i++) {
            $number .= mt_rand(0, 9);
        }
        return $number;
    }

    /**
     * Generates a fake boolean value.
     *
     * @return bool
     */
    public static function boolean(): bool
    {
        return (bool)random_int(0, 1);
    }
}
