<?php
/**
 * Created by PhpStorm.
 * User: jevge
 * Date: 2018-02-02
 * Time: 16:10
 */

namespace HoneyComb\Menu\Enum;

use HoneyComb\Starter\Enum\Enumerable;

class HCMenuUrlTargetEnum extends Enumerable
{
    /**
     * @return HCMenuUrlTargetEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function self(): HCMenuUrlTargetEnum
    {
        return self::make('_self', trans('HCMenu::menu.self'));
    }

    /**
     * @return HCMenuUrlTargetEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function blank(): HCMenuUrlTargetEnum
    {
        return self::make('_blank', trans('HCMenu::menu.blank'));
    }
}