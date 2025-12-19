<?php

namespace App;

use App\Exceptions\ValidatorException;

/**
 * Class Validator
 * 
 * This class is used to validate data.
 * 
 * Usage as:
 * Validator::make($request->all(), [
 *   'name' => 'required|string|unique:attributes,name',
 *   'code' => 'required|string|unique:attributes,code',
 *   'type' => 'required|in:' . implode(',', ['value1', 'value2', 'value3']),
 * ])
 * 
 */
class Validator
{
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
    private function check(string $key, string $rule, $value): bool
    {
        if (!$key || !$rule) throw new ValidatorException("The validation key and rule must be provided.");

        if ($rule === 'required') {
            if (is_null($value) || $value === '') {
                $this->addError($key, "The field is required");
                return false;
            }
        }

        if (str_starts_with($rule, 'in:')) {
            $alloweds = explode(',', substr($rule, 3));
            if (!in_array($value, $alloweds)) {
                $this->addError($key, "The field must be one of the following values: " . implode(', ', $alloweds));
                return false;
            }
        }

        if (str_starts_with($rule, 'unique:')) {
            // $parts = explode(',', substr($rule, 7));
            // $table = $parts[0] ?? null;
            // $column = $parts[1] ?? $key;

            // if (is_null($table)) {
            //     throw new ValidatorException("The 'unique' rule for must specify a table.");
            // }

            // TODO: Check uniqueness in the database
        }

        return true;
    }

    /**
     * Validate the data.
     */
    private function validate(): void
    {
        $this->clearErrors();

        foreach ($this->rules as $key => $rules) {
            $value = is_array($this->data) ?
                ($this->data[$key] ?? null) : // array case
                ($this->data->$key ?? null); // object case

            if (is_string($rules)) {
                $rules = explode('|', $rules);
            } else if (!is_array($rules)) {
                throw new ValidatorException("The rules for '$key' must be a string or an array.");
            }

            foreach (array_unique($rules) as $rule) {
                $this->check($key, $rule, $value);
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
