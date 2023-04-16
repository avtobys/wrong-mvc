<?php

/**
 * @file
 * @brief встраивание шаблонов
 * 
 */

namespace Wrong\Html;

use Wrong\Models\Templates;

/**
 * @brief Template класс встраивает шаблоны
 * 
 */

class Template
{
    /**
     * Встраивает шаблон по его идентификатору после проверки прав доступа на чтение
     * 
     * @param int $template_require_id 
     */
    public static function require($template_require_id)
    {
        foreach (array_filter(array_keys($GLOBALS), function ($key) {
            return $key != 'GLOBALS' && substr($key, 0, 1) != '_' && $key != 'template_require_id';
        }) as $k) {
            ${$k} = &$GLOBALS[$k];
        }
        $template_require = Templates::find($template_require_id);
        if ($user->access()->read($template_require)) {
            require $_SERVER['DOCUMENT_ROOT'] . $template_require->file;
        }
        foreach (get_defined_vars() as $k => $var) {
            $GLOBALS[$k] = $var; 
        }
    }
}
