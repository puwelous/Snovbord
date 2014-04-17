<?php

require_once('ICAbstractEnum.php');

/**
 * Class representing enumeration objects.
 * For newer version of PHP there is already supporting library method 
 * which allows us to substitute this not necessary interface and related
 * class anymore.
 * 
 * For this purpose see PHP documentation:
 * <a href="http://www.php.net/manual/en/class.splenum.php">PHP SplEnum</a>  
 * 
 * 
 * @author Pavol Daňo
 * @version 1.0 
 * @file
 */
abstract class CAbstractEnum implements ICAbstractEnum{

    /**
     * Static method for retrieving reflection information about constants of the class.
     * @param string $class
     *  name of a class whose constants are retrieved
     * @return array
     *  array of constants that belong to the specifies class
     */
    private static function getConstants($class) {
            $reflect = new ReflectionClass(
                            $class
            );

        return $reflect->getConstants();
    }

    /**
     * Method checking if name specified as \param $name is present in a class.
     * 
     * @param string $class
     *  String representation of class which implements the interface.
     *  It was defined because of lower versions of PHP which does not
     *  support reflection that much as newer versions.
     * @param string $name
     *  String representation of an enumeration item name that we check the presence for.
     * @param boolean $strict
     *  Boolean flag expressing the way we want checking to be performed 
     *  in a way of case sensitive or case insensitive check.
     * @retval boolean
     *  Result saying the parameter is present as a name in the enumeration or not.
     */
    public static function isValidName($class, $name, $strict = false) {
        $constants = self::getConstants($class);

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    /**
     * Method checking if value specified as \param $value is present in a class.
     * 
     * @param string $class
     *  String representation of class which implements the interface.
     *  It was defined because of lower versions of PHP which does not
     *  support reflection that much as newer versions.
     * @param string $value
     *  String representation of an enumeration item value that we check the presence for.
     * @retval boolean
     *  Result saying the parameter is present as a value in the enumeration or not.
     */        
    public static function isValidValue( $class, $value) {
        $values = array_values(self::getConstants( $class ));
        return in_array($value, $values, $strict = true);
    }

}

?>
