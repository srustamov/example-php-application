<?php

use App\Support\CSRF;

function base_path(string $path = ''): string
{
    return BASE_PATH . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}

function view($template, $data = []): bool|string
{
    extract($data);

    ob_start();

    include base_path("/views/{$template}.php");

    return ob_get_clean();
}


function csrf_field(): string
{
    return sprintf(
        '<input type="hidden" name="%s" value="%s">',
        CSRF::$tokenName,
        CSRF::getToken()
    );
}

function validate(array $data, array $rules): array
{
    $errors = [];

    foreach ($rules as $field => $_rule) {

        $rules = explode('|', $_rule);

        foreach ($rules as $rule) {
            if ($rule === 'required' && empty($data[$field])) {
                $errors[$field][] = 'The field is required';
            }

            if(str_contains($rule, 'min:')) {
                $min = explode(':', $rule)[1];

                if (strlen($data[$field]) < $min) {
                    $errors[$field][] = 'The  field must be at least ' . $min . ' characters';
                }
            }

            if(str_contains($rule, 'max:')) {
                $max = explode(':', $rule)[1];

                if (strlen($data[$field]) > $max) {
                    $errors[$field][] = 'The field must be less than ' . $max . ' characters';
                }
            }
        }
    }

    return $errors;
}