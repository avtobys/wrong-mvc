<?php

/**
 * @file
 * @brief интерфейс управления моделями действий
 * 
 */

namespace Wrong\Models;

/**
 * @brief ModelsInterface интерфейс управления моделями
 * 
 */

interface ModelsInterface
{
    /**
     * создние новой модели(компонента)
     * 
     * @param mixed аргумент
     * 
     */
    public static function create($arg);
}
