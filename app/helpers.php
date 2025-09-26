<?php

if (! function_exists('htmlFormNotationToDot')) {
    /**
     * It is not really safe and might lead to some bugs but not sure how else to do that
     *
     * @param  string  $name  The field name ('field[0]' or 'user[name]')
     * @return string The converted field name ('field.0' or 'user.name')
     */
    function htmlFormNotationToDot(string $name): string
    {
        return str_replace(['[', ']'], ['.', ''], $name);
    }
}
