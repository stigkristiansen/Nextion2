<?php
declare(strict_types=1);

class DeviceTypeGenericNumber {
    private static $implementedType = 'NUMBR';
    private static $implementedTraits = [
        'Number'
    ];
	    
	private static $displayStatusPrefix = false;
    
	use HelperDeviceType;
    
	public static function getPosition(){
        return 90;
    }
    
	public static function getCaption(){
        return 'Integer';
    }
	

}

DeviceTypeRegistry::register('GenericNumber');

?>