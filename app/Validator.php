<?php

namespace App;

use App\Exceptions\ValidatorException;

/**
 * Class Validator
 * 
 * This class is used to validate data.
 * 
 * Usage:
 * Validator::make($request->all(), [
 *   'name' => 'required|string|unique:attributes,name',
 *   'code' => 'required|string|unique:attributes,code',
 *   'type' => 'required|in:' . implode(',', ['value1', 'value2', 'value3']),
 * ])
 * 
 */
class Validator
{
    const VALIDATIONS = [ // More frequent first for performance
        'required' => [
            'regex' => '/^required$/',
            'message' => 'The field is required',
            'function' => function ($value) {
                return !is_null($value) && $value !== '';
            }
        ],
        'string' => [
            'regex' => '/^string$/',
            'message' => 'The field must be a string',
            'function' => function ($value) {
                return is_string($value);
            }
        ],
        'integer' => [
            'regex' => '/^integer$/',
            'message' => 'The field must be an integer',
            'function' => function ($value) {
                return is_int($value);
            }
        ],
        'in' => [
            'regex' => '/^in:(.+)$/',
            'message' => 'The field must be one of the following values: {values}',
            'function' => function ($value, $params) {
                $allowed = explode(',', $params);
                return in_array($value, $allowed);
            }
        ],
        'unique' => [
            'regex' => '/^unique:(\w+),(\w+)$/',
            'message' => 'The field already exists in the database',
            'function' => function ($value, $params) {
                // TODO: This is a placeholder for uniqueness check.
                return true;
            }
        ],
    ];

    /**
     * Data to validate.
     */
    private $data = [];

    /**
     * Validation rules.
     */
    private $rules = [];

    /**
     * Validation rules.
     */
    public $errors = [];

    /**
     * Validator class constructor
     * 
     * @param array|object $data Data to validate
     * @param array $rules Validation rules
     */
    public function __construct(array|object $data = [], array $rules = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    /**
     * Add an error message for a specific key.
     * 
     * @param string $key The key of the data
     * @param string $message The error message
     */
    private function addError(string $key, string $message): void
    {
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = [];
        }
        $this->errors[$key][] = $message;
    }

    /**
     * Clear all validation errors.
     */
    private function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * Check if there is any validation error.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Check a single validation rule.
     * 
     * @param string $key The key of the data
     * @param string $rule The validation rule
     * @param mixed $value The value to validate
     * @return bool True if the rule passes, false otherwise
     */
    private function check(string $key, string $part, $value): ?string
    {
        if (!$key || !$part) throw new ValidatorException("The validation key and part must be provided.");

        foreach (self::VALIDATIONS as $key => $validation) {
            if (preg_match($validation['regex'], $part, $matches)) {
                $params = $matches[1] ?? null;

                $ok = $validation['function']($value, $params);
                if (!$ok) {
                    $message = $validation['message'];
                    if ($params && str_contains($message, '{values}')) {
                        $message = str_replace('{values}', $params, $message);
                    }

                    return $message;
                }
            }
        }


        return null;
    }

    /**
     * Normalize the parts of a rule.
     * 
     * @param string|array $part The rule part
     * @return array The normalized rule parts
     */
    private function normalizePart($part): ?array
    {
        if (is_string($part)) return explode('|', $part);
        if (is_array($part)) return $part;

        throw new ValidatorException("The parts must be a string or an array.");
    }

    /**
     * Validate the data.
     */
    private function validate(): void
    {
        $this->clearErrors();

        foreach (array_unique($this->rules) as $key => $parts) {
            $value = Helpers::dataGet($this->data, $key);

            $parts = $this->normalizePart($parts);

            foreach ($parts as $part) {
                $error = $this->check($key, $part, $value);
                if ($error) $this->addError($key, $error);
            }
        }
    }

    /**
     * Create a new Validator instance.
     * 
     * @param array|object $data Data to validate
     * @param array $rules Validation rules
     */
    static public function make(array|object $data = [], array $rules = []): Validator
    {
        if (empty($rules)) throw new ValidatorException("No validation rules provided.");

        return new Validator($data, $rules);
    }
}
