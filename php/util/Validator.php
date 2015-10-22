<?php

class Util_Validator
{
    /**
     * The data under validation.
     *
     * @var array
     */
    protected $data;

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules;

    /**
     * The failed validation rules.
     *
     * @var array
     */
    protected $failedRules = array();

    /**
     * The error messages.
     *
     * @var array
     */
    protected $messages = array();

    /**
     * The person error message
     *
     * @var array
     */
    protected $personMessage = array();

    protected $messageFormats = array(
        "accepted" => "The :attribute must be accepted.",
        "active_url" => "The :attribute is not a valid URL.",
        "after" => "The :attribute must be a date after :date.",
        "alpha" => "The :attribute may only contain letters.",
        "alpha_dash" => "The :attribute may only contain letters, numbers, and dashes.",
        "alpha_num" => "The :attribute may only contain letters and numbers.",
        "array" => "The :attribute must be an array.",
        "before" => "The :attribute must be a date before :date.",
        "between" => array(
            "numeric" => "The :attribute must be between :min and :max.",
            "file" => "The :attribute must be between :min and :max kilobytes.",
            "string" => "The :attribute must be between :min and :max characters.",
            "array" => "The :attribute must have between :min and :max items.",
        ),
        "confirmed" => "The :attribute confirmation does not match.",
        "date" => "The :attribute is not a valid date.",
        "date_format" => "The :attribute does not match the format :format.",
        "different" => "The :attribute and :other must be different.",
        "digits" => "The :attribute must be :digits digits.",
        "digits_between" => "The :attribute must be between :min and :max digits.",
        "email" => "The :attribute must be a valid email address.",
        "exists" => "The selected :attribute is invalid.",
        "image" => "The :attribute must be an image.",
        "in" => "The selected :attribute is invalid.",
        "integer" => "The :attribute must be an integer.",
        "ip" => "The :attribute must be a valid IP address.",
        "max" => array(
            "numeric" => "The :attribute may not be greater than :max.",
            "file" => "The :attribute may not be greater than :max kilobytes.",
            "string" => "The :attribute may not be greater than :max characters.",
            "array" => "The :attribute may not have more than :max items.",
        ),
        "mimes" => "The :attribute must be a file of type: :values.",
        "min" => array(
            "numeric" => "The :attribute must be at least :min.",
            "file" => "The :attribute must be at least :min kilobytes.",
            "string" => "The :attribute must be at least :min characters.",
            "array" => "The :attribute must have at least :min items.",
        ),
        "not_in" => "The selected :attribute is invalid.",
        "numeric" => "The :attribute must be a number.",
        "regex" => "The :attribute format is invalid.",
        "required" => "The :attribute field is required.",
        "required_if" => "The :attribute field is required when :other is :value.",
        "required_with" => "The :attribute field is required when :values is present.",
        "required_without" => "The :attribute field is required when :values is not present.",
        "required_without_all" => "The :attribute field is required when none of :values are present.",
        "same" => "The :attribute and :other must match.",
        "size" => array(
            "numeric" => "The :attribute must be :size.",
            "file" => "The :attribute must be :size kilobytes.",
            "string" => "The :attribute must be :size characters.",
            "array" => "The :attribute must contain :size items.",
        ),
        "unique" => "The :attribute has already been taken.",
        "url" => "The :attribute format is invalid.",
    );


    /**
     * The size related validation rules.
     *
     * @var array
     */
    protected $sizeRules = array('Size', 'Between', 'Min', 'Max');

    /**
     * The numeric related validation rules.
     *
     * @var array
     */
    protected $numericRules = array('Numeric', 'Integer');

    /**
     * The validation rules that imply the field is required.
     *
     * @var array
     */
    protected $implicitRules = array(
        'Required', 'RequiredWith', 'RequiredWithAll', 'RequiredWithout', 'RequiredWithoutAll', 'RequiredIf', 'Accepted'
    );


    /**
     * 构造函数
     *
     * @param $data
     * @param $rules
     */
    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $this->explodeRules($rules);
    }

    /**
     * Determine if the data passes the validation rules.
     *
     * @return bool
     */
    public function passes()
    {
        foreach ($this->rules as $attribute => $rules) {
            foreach ($rules as $rule) {
                $this->validate($attribute, $rule);
            }
        }

        return count($this->messages) === 0;
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails()
    {
        return !$this->passes();
    }

    /**
     * Get the message container for the validator.
     *
     * @return array
     */
    public function messages()
    {
        if (!$this->messages) $this->passes();

        return $this->messages;
    }

    /**
     * An alternative more semantic shortcut to the message container.
     *
     * @return array
     */
    public function errors()
    {
        if (!$this->messages) $this->passes();

        return $this->messages;
    }

    /**
     * Require a certain number of parameters to be present.
     *
     * @param  int $count
     * @param  array $parameters
     * @param  string $rule
     * @return void
     * @throws InvalidArgumentException
     */
    protected function requireParameterCount($count, $parameters, $rule)
    {
        if (count($parameters) < $count) {
            throw new InvalidArgumentException("Validation rule $rule requires at least $count parameters.");
        }
    }

    /**
     * Get the data under validation.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the data under validation.
     *
     * @param  array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }


    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Set the validation rules.
     *
     * @param  array $rules
     *
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $this->explodeRules($rules);

        return $this;
    }

    /**
     * Explode the rules into an array of rules.
     *
     * @param  string|array $rules
     * @return array
     */
    protected function explodeRules($rules)
    {
        foreach ($rules as $attribute => &$rule) {
            if(is_string($rule)) {
                $conditions = explode('|', $rule);
                foreach($conditions as $key => $condition) {
                    if(strstr($condition,'message')) {
                        $name = explode(':', $condition, 2);
                        $this->personMessage[$attribute] = trim($name[1]);
                        unset($conditions[$key]);
                    }
                    $rule = $conditions;
                }
            }
        }
        return $rules;
    }

    /**
     * Extract the rule name and parameters from a rule.
     *
     * @param  string $rule
     * @return array
     */
    protected function parseRule($rule)
    {
        $parameters = array();

        // The format for specifying validation rules and parameters follows an
        // easy {rule}:{parameters} formatting convention. For instance the
        // rule "Max:3" states that the value may only be three letters.
        if (strpos($rule, ':') !== false) {
            list($rule, $parameter) = explode(':', $rule, 2);

            $parameters = $this->parseParameters($rule, $parameter);
        }

        return array(Util_String::studly($rule), $parameters);
    }


    /**
     * Parse a parameter list.
     *
     * @param  string $rule
     * @param  string $parameter
     * @return array
     */
    protected function parseParameters($rule, $parameter)
    {
        if (strtolower($rule) == 'regex') {
            return array($parameter);
        }

        return str_getcsv($parameter);
    }


    /**
     * Get the value of a given attribute.
     *
     * @param  string $attribute
     * @return mixed
     */
    protected function getValue($attribute)
    {
        return isset($this->data[$attribute]) ? $this->data[$attribute] : null;
    }


    protected function validate($attribute, $rule)
    {
        if (trim($rule) == '') return;

        list($rule, $parameters) = $this->parseRule($rule);

        // We will get the value for the given attribute from the array of data and then
        // verify that the attribute is indeed validatable. Unless the rule implies
        // that the attribute is required, rules are not run for missing values.
        $value = $this->getValue($attribute);

        $validatable = $this->isValidatable($rule, $attribute, $value);

        $method = "validate{$rule}";

        if ($validatable && !$this->$method($attribute, $value, $parameters, $this)) {
            $this->addFailure($attribute, $rule, $parameters);
        }
    }

    /**
     * Add a failed rule and error message to the collection.
     *
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return void
     */
    protected function addFailure($attribute, $rule, $parameters)
    {
        $this->addError($attribute, $rule, $parameters);

        $this->failedRules[$attribute][$rule] = $parameters;
    }

    /**
     * Add an error message to the validator's collection of messages.
     *
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return void
     */
    protected function addError($attribute, $rule, $parameters)
    {
        $message = $this->getMessage($attribute, $rule);

        $message = $this->doReplacements($message, $attribute, $rule, $parameters);

        $this->messages[$attribute][] = $message;

        $this->messages = array_merge($this->messages , array('personMessage' => $this->personMessage));

    }


    /**
     * Get the validation message for an attribute and rule.
     *
     * @param  string $attribute
     * @param  string $rule
     * @return string
     */
    protected function getMessage($attribute, $rule)
    {
        $lowRule = Util_String::snake($rule);

        return $this->messageFormats[$lowRule];
    }

    /**
     * Replace all error message place-holders with actual values.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function doReplacements($message, $attribute, $rule, $parameters)
    {
        $message = str_replace(':attribute', $attribute, $message);

        if (method_exists($this, $replacer = "replace{$rule}"))
        {
            $message = $this->$replacer($message, $attribute, $rule, $parameters);
        }

        return $message;
    }


    /**
     * Determine if the attribute is validatable.
     *
     * @param  string $rule
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function isValidatable($rule, $attribute, $value)
    {
        return $this->presentOrRuleIsImplicit($rule, $attribute, $value) &&
        $this->passesOptionalCheck($attribute);
    }

    /**
     * Determine if the field is present, or the rule implies required.
     *
     * @param  string $rule
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function presentOrRuleIsImplicit($rule, $attribute, $value)
    {
        return $this->validateRequired($attribute, $value) || $this->isImplicit($rule);
    }


    /**
     * Determine if a given rule implies the attribute is required.
     *
     * @param  string $rule
     * @return bool
     */
    protected function isImplicit($rule)
    {
        return in_array($rule, $this->implicitRules);
    }

    /**
     * Determine if the attribute passes any optional check.
     *
     * @param  string $attribute
     * @return bool
     */
    protected function passesOptionalCheck($attribute)
    {
        if ($this->hasRule($attribute, array('Optional'))) {
            return array_key_exists($attribute, $this->data) || array_key_exists($attribute, $this->files);
        } else {
            return true;
        }
    }

    /**
     * Determine if the given attribute has a rule in the given set.
     *
     * @param  string $attribute
     * @param  array $rules
     * @return bool
     */
    protected function hasRule($attribute, $rules)
    {
        $rules = (array)$rules;

        // To determine if the attribute has a rule in the ruleset, we will spin
        // through each of the rules assigned to the attribute and parse them
        // all, then check to see if the parsed rules exists in the arrays.
        foreach ($this->rules[$attribute] as $rule) {
            list($rule, $parameters) = $this->parseRule($rule);

            if (in_array($rule, $rules)) return true;
        }

        return false;
    }

    /**
     * Validate that an attribute was "accepted".
     *
     * This validation rule implies the attribute is "required".
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    protected function validateAccepted($attribute, $value)
    {
        $acceptable = array('yes', 'on', '1', 1);

        return ($this->validateRequired($attribute, $value) && in_array($value, $acceptable, true));
    }

    /**
     * Validate that an attribute is an active URL.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    protected function validateActiveUrl($attribute, $value)
    {
        $url = str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value));

        return checkdnsrr($url);
    }

    /**
     * Validate that an attribute has a matching confirmation.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    protected function validateConfirmed($attribute, $value)
    {
        return $this->validateSame($attribute, $value, array($attribute.'_confirmation'));
    }

    /**
     * Validate that two attributes match.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    protected function validateSame($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'same');

        $other = $this->getValue($parameters[0]);

        return (isset($other) && $value == $other);
    }

    /**
     * Validate that an attribute is different from another attribute.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    protected function validateDifferent($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'different');

        $other = $parameters[0];

        return isset($this->data[$other]) && $value != $this->data[$other];
    }

    /**
     * Validate that an attribute is a valid e-mail address.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    protected function validateEmail($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate that an attribute is a valid IP.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    protected function validateIp($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate that an attribute is a valid URL.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    protected function validateUrl($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }


    /**
     * Validate that an attribute contains only alphabetic characters.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateAlpha($attribute, $value)
    {
        return preg_match('/^\pL+$/u', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateAlphaNum($attribute, $value)
    {
        return preg_match('/^[\pL\pN]+$/u', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateAlphaDash($attribute, $value)
    {
        return preg_match('/^[\pL\pN_-]+$/u', $value);
    }

    /**
     * Validate that an attribute passes a regular expression check.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateRegex($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'regex');

        return preg_match($parameters[0], $value);
    }

    /**
     * Validate that an attribute is a valid date.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateDate($attribute, $value)
    {
        if ($value instanceof DateTime) return true;

        if (strtotime($value) === false) return false;

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }

    /**
     * Validate that an attribute matches a date format.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateDateFormat($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'date_format');

        $parsed = date_parse_from_format($parameters[0], $value);

        return $parsed['error_count'] === 0 && $parsed['warning_count'] === 0;
    }

    /**
     * Validate the date is before a given date.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateBefore($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'before');

        if (!($date = strtotime($parameters[0]))) {
            return strtotime($value) < strtotime($this->getValue($parameters[0]));
        } else {
            return strtotime($value) < $date;
        }
    }

    /**
     * Validate the date is after a given date.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateAfter($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'after');

        if (!($date = strtotime($parameters[0]))) {
            return strtotime($value) > strtotime($this->getValue($parameters[0]));
        } else {
            return strtotime($value) > $date;
        }
    }

    /**
     * Get the size of an attribute.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return mixed
     */
    protected function getSize($attribute, $value)
    {
        $hasNumeric = $this->hasRule($attribute, $this->numericRules);

        // This method will determine if the attribute is a number, string, or file and
        // return the proper size accordingly. If it is a number, then number itself
        // is the size. If it is a file, we take kilobytes, and for a string the
        // entire length of the string will be considered the attribute size.
        if (is_numeric($value) && $hasNumeric) {
            return $this->getValue($attribute);
        } elseif (is_array($value)) {
            return count($value);
        } else {
            return $this->getStringSize($value);
        }
    }

    /**
     * Get the size of a string.
     *
     * @param  string $value
     * @return int
     */
    protected function getStringSize($value)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($value, 'UTF-8');
        }

        return strlen($value);
    }

    /**
     * Validate that an attribute is an array.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateArray($attribute, $value)
    {
        return is_array($value);
    }

    /**
     * Validate that an attribute is numeric.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateNumeric($attribute, $value)
    {
        return is_numeric($value);
    }

    /**
     * Validate that an attribute is an integer.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateInteger($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate that an attribute has a given number of digits.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateDigits($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'digits');

        return $this->validateNumeric($attribute, $value)
        && strlen((string)$value) == $parameters[0];
    }

    /**
     * Validate that an attribute is between a given number of digits.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateDigitsBetween($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'digits_between');

        $length = strlen((string)$value);

        return $length >= $parameters[0] && $length <= $parameters[1];
    }

    /**
     * Validate the size of an attribute.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateSize($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'size');

        return $this->getSize($attribute, $value) == $parameters[0];
    }


    /**
     * Validate the size of an attribute is between a set of values.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateBetween($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'between');

        $size = $this->getSize($attribute, $value);

        return $size >= $parameters[0] && $size <= $parameters[1];
    }

    /**
     * Validate the size of an attribute is greater than a minimum value.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateMin($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'min');

        return $this->getSize($attribute, $value) >= $parameters[0];
    }

    /**
     * Validate the size of an attribute is less than a maximum value.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateMax($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'max');

        return $this->getSize($attribute, $value) <= $parameters[0];
    }

    /**
     * Validate an attribute is contained within a list of values.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateIn($attribute, $value, $parameters)
    {
        return in_array($value, $parameters);
    }

    /**
     * Validate an attribute is not contained within a list of values.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateNotIn($attribute, $value, $parameters)
    {
        return !in_array($value, $parameters);
    }


    /**
     * "Validate" optional attributes.
     *
     * Always returns true, just lets us put optional in rules.
     *
     * @return bool
     */
    protected function validateOptional()
    {
        return true;
    }

    /**
     * Validate that a required attribute exists.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateRequired($attribute, $value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        }

        return true;
    }

    /**
     * Validate the given attribute is filled if it is present.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    protected function validateFilled($attribute, $value)
    {
        if (array_key_exists($attribute, $this->data) || array_key_exists($attribute, $this->files)) {
            return $this->validateRequired($attribute, $value);
        } else {
            return true;
        }
    }

    /**
     * Determine if any of the given attributes fail the required test.
     *
     * @param  array $attributes
     * @return bool
     */
    protected function anyFailingRequired(array $attributes)
    {
        foreach ($attributes as $key) {
            if (!$this->validateRequired($key, $this->getValue($key))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if all of the given attributes fail the required test.
     *
     * @param  array $attributes
     * @return bool
     */
    protected function allFailingRequired(array $attributes)
    {
        foreach ($attributes as $key) {
            if ($this->validateRequired($key, $this->getValue($key))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate that an attribute exists when any other attribute exists.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    protected function validateRequiredWith($attribute, $value, $parameters)
    {
        if (!$this->allFailingRequired($parameters)) {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate that an attribute exists when all other attributes exists.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    protected function validateRequiredWithAll($attribute, $value, $parameters)
    {
        if (!$this->anyFailingRequired($parameters)) {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate that an attribute exists when another attribute does not.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    protected function validateRequiredWithout($attribute, $value, $parameters)
    {
        if ($this->anyFailingRequired($parameters)) {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate that an attribute exists when all other attributes do not.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    protected function validateRequiredWithoutAll($attribute, $value, $parameters)
    {
        if ($this->allFailingRequired($parameters)) {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }

    /**
     * Validate that an attribute exists when another attribute has a given value.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  mixed $parameters
     * @return bool
     */
    protected function validateRequiredIf($attribute, $value, $parameters)
    {
        $this->requireParameterCount(2, $parameters, 'required_if');

        if ($parameters[1] == $this->getValue($parameters[0])) {
            return $this->validateRequired($attribute, $value);
        }

        return true;
    }




    /**
     * Replace all place-holders for the between rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceBetween($message, $attribute, $rule, $parameters)
    {
        return str_replace(array(':min', ':max'), $parameters, $message);
    }

    /**
     * Replace all place-holders for the digits rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceDigits($message, $attribute, $rule, $parameters)
    {
        return str_replace(':digits', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the digits (between) rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceDigitsBetween($message, $attribute, $rule, $parameters)
    {
        return str_replace(array(':min', ':max'), $parameters, $message);
    }

    /**
     * Replace all place-holders for the size rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceSize($message, $attribute, $rule, $parameters)
    {
        return str_replace(':size', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the min rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceMin($message, $attribute, $rule, $parameters)
    {
        return str_replace(':min', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the max rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceMax($message, $attribute, $rule, $parameters)
    {
        return str_replace(':max', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the in rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceIn($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(', ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the not_in rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceNotIn($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(', ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the mimes rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceMimes($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(', ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the required_with rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceRequiredWith($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(' / ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the required_without rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceRequiredWithout($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(' / ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the required_without_all rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceRequiredWithoutAll($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(' / ', $parameters), $message);
    }

    /**
     * Replace all place-holders for the required_if rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceRequiredIf($message, $attribute, $rule, $parameters)
    {
        return str_replace(array(':other', ':value'), $parameters, $message);
    }

    /**
     * Replace all place-holders for the same rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceSame($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the different rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceDifferent($message, $attribute, $rule, $parameters)
    {
        return str_replace(':other', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the date_format rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceDateFormat($message, $attribute, $rule, $parameters)
    {
        return str_replace(':format', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the before rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceBefore($message, $attribute, $rule, $parameters)
    {
        if ( ! ($date = strtotime($parameters[0])))
        {
            return str_replace(':date', $parameters[0], $message);
        }
        else
        {
            return str_replace(':date', $parameters[0], $message);
        }
    }

    /**
     * Replace all place-holders for the after rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceAfter($message, $attribute, $rule, $parameters)
    {
        if ( ! ($date = strtotime($parameters[0])))
        {
            return str_replace(':date', $parameters[0], $message);
        }
        else
        {
            return str_replace(':date', $parameters[0], $message);
        }
    }

}
