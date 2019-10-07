<?php
namespace MicroBundle\Enums;


abstract class OffertStatusEnum
{
    const BASKET = "W koszyku";
    const ACTIVE = "Aktywne";
    const CLOSED = "Zamknięte";

    /** @var array user friendly named type */
    protected static $typeName = [self::BASKET => "W koszyku", self::ACTIVE => "Aktywne", self::CLOSED => "Zamknięte",];

    /**
     * @param  string $typeShortName
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableStatuses()
    {
        return [self::BASKET, self::ACTIVE, self::CLOSED,];
    }
}
