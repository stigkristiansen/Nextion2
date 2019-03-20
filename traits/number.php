<?php
declare(strict_types=1);
class DeviceTraitNumber
{
    const propertyPrefix = 'Number';
    use HelperNumberDevice;
    
	public static function getColumns(){
        return [
            [
                'label' => 'Variable',
                'name'  => self::propertyPrefix . 'ID',
                'width' => '200px',
                'add'   => 0,
                'edit'  => [
                    'type' => 'SelectVariable'
                ]
            ]
        ];
    }
	
    public static function getStatus($configuration){
        return self::getCompatibility($configuration[self::propertyPrefix . 'ID'], $configuration['Mapping']);
    }
	
    public static function getStatusPrefix(){
        return 'Number: ';
    }
	
    public static function doQuery($configuration){
		if (IPS_VariableExists($configuration[self::propertyPrefix . 'ID'])){
			$command = $configuration['Mapping'];
			$value = self::getVariableValue($configuration[self::propertyPrefix . 'ID']);
			$command.=".val=".(string)$value;
            return [
				'command' => $command
            ];
        } else {
            return [];
        }
    }
	
    public static function doExecute($configuration, $Value){
		if (self::updateVariable($configuration[self::propertyPrefix . 'ID'], $Value)){
			$text = $Value;
			
			return [
				'ids'    => [$configuration['ID']],
				'status' => 'SUCCESS',
				'states' => [
					'text<'     => $text,
					'online' => true
				]
			];
		} else {
			return [
				'ids'       => [$configuration['ID']],
				'status'    => 'ERROR',
				'errorCode' => 'deviceNotResponding'
			];
		}
    }

    public static function getObjectIDs($configuration){
        return [
            $configuration[self::propertyPrefix . 'ID']
        ];
    }
	
	public static function getMappings($configuration){
		return [
            $configuration['Mapping']
        ];
	}
}

?>