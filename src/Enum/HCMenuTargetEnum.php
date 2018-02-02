<?php
/**
 * Created by PhpStorm.
 * User: jevge
 * Date: 2018-02-02
 * Time: 16:10
 */

namespace HoneyComb\Menu\Enum;

use HoneyComb\Starter\Enum\Enumerable;

class HCMenuTargetEnum extends Enumerable
{
    /**
     * @return HCMenuTargetEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function url(): HCMenuTargetEnum
    {
        return self::make('url', trans('HCMenu::menu.url'));
    }

    /**
     * @return HCMenuTargetEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function page(): HCMenuTargetEnum
    {
        return self::make('page', trans('HCMenu::menu.page'));
    }

    /**
     * @return HCMenuTargetEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function parent(): HCMenuTargetEnum
    {
        return self::make('parent', trans('HCMenu::menu.parent'));
    }
}