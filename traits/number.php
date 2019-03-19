<?php
declare(strict_types=1);
class DeviceTraitNumber
{
    const propertyPrefix = 'Number';
    use HelperNumberDevice;
    
	public static function getColumns()
    {
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
    public static function getStatus($configuration)
    {
        return self::getNumberCompatibility($configuration[self::propertyPrefix . 'ID'], $configuration['Mapping']);
    }
    public static function getStatusPrefix()
    {
        return 'Number: ';
    }
    public static function doQuery($configuration)
    {
		IPS_LogMessage('DeviceTraitNumber','Inside DeviceTraitNumber::doQuery');
        if (IPS_VariableExists($configuration[self::propertyPrefix . 'ID'])) {
			$command = $configuration['Mapping'];
			$value = self::getNumberValue($configuration[self::propertyPrefix . 'ID']);
			$command.=".val=\"".$value."\"";
            return [
				'command' => $command
            ];
        } else {
            return [];
        }
    }
    public static function doExecute($configuration, $Value)
    {
		if (self::changeNumber($configuration[self::propertyPrefix . 'ID'], $Value)) {
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

    public static function getObjectIDs($configuration)
    {
        return [
            $configuration[self::propertyPrefix . 'ID']
        ];
    }
	
	public static function getMappings($configuration) {
		return [
            $configuration['Mapping']
        ];
	}
	
    public static function supportedTraits($configuration)
    {
        return [
            'action.devices.traits.Number'
        ];
    }
    public static function supportedCommands()
    {
        return [
            'action.devices.commands.Number'
        ];
    }
    public static function getAttributes()
    {
        return [];
    }
}

?>