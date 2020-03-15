<?php
namespace x51\classes\events;

class Events
{
    protected static $_events = [];
    
    /**
     * Установить обработчик на событие
     *
     * @param string $eventName - название события
     * @param callable $handlerFunc - обработчик
     * @param object|string $_class
     * @return void
     */
    public static function on(string $eventName, callable $handlerFunc, $_class = '') {
        if (!isset(static::$_events[$eventName])) {
            static::$_events[$eventName] = [];
        }
        static::$_events[$eventName][$_class][] = $handlerFunc;
    } // end func on

    /**
     * Убрать обработчик на событие
     *
     * @param string $eventName - название события
     * @param callable $handlerFunc - обработчик
     * @param object|string $_class
     * @return void
     */
    public static function off(string $eventName, callable $handlerFunc, $_class = '')
    {
        if (isset(static::$_events[$eventName]) && isset(static::$_events[$eventName][$_class])) {
            $finded = array_keys(static::$_events[$eventName][$_class], $handlerFunc, true);
            if ($finded) {
                foreach ($finded as $fk) {
                    unset(static::$_events[$eventName][$_class][$fk]);
                } 
            }
        }
    } // end func on
    
    /**
     * Вызвать событие. Если обработчик возвращает false - прекратить обработку
     *
     * @param string $eventName
     * @param object|string $_class
     * @param object $eventData
     * @return void
     */
    public static function trigger(string $eventName, $_class = '', $eventData = null) {
        if (!empty(static::$_events[$eventName])) {
            if (isset(static::$_events[$eventName][$_class])) {
                $eventKey = $_class;
            }
            if (!isset($eventKey) && is_object($_class)) {
                $parents = [get_class($_class)];
                array_push($parents, ...array_values(class_parents($_class)));
                foreach ($parents as $classname) {
                    if (isset(static::$_events[$eventName][$classname])) {
                        $eventKey = $classname;
                        break;
                    }
                }
            }
            if (isset($eventKey)) {
                foreach (static::$_events[$eventName][$eventKey] as $callback) {
                    $r = null;
                    $r = $callback($eventData);
                    if (!is_null($r) && $r == false) {
                        break;
                    }
                }
            }
        }
    } // end trigger


} // end class