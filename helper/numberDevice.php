<?php
declare(strict_types=1);

trait HelperNumberDevice {
    private static function getNumberCompatibility($variableID, $mapping){
        if (!IPS_VariableExists($variableID)) {
            return 'Missing';
        }
        $targetVariable = IPS_GetVariable($variableID);

        if ($targetVariable['VariableCustomAction'] != 0) {
            $profileAction = $targetVariable['VariableCustomAction'];
        } else {
            $profileAction = $targetVariable['VariableAction'];
        }
        if (!($profileAction > 10000)) {
            return 'Action required';
        }
		
		if(strlen($mapping)==0) {
			return 'Mapping required';
		}
        return 'OK';
    }
    private static function getNumberValue($variableID){
        return GetValue($variableID);
    }
	
    private static function changeNumber($variableID, $value){
		if (!IPS_VariableExists($variableID)) {
            return false;
        }
        $targetVariable = IPS_GetVariable($variableID);
        if ($targetVariable['VariableCustomProfile'] != '') {
            $profileName = $targetVariable['VariableCustomProfile'];
        } else {
            $profileName = $targetVariable['VariableProfile'];
        }
        if ($targetVariable['VariableCustomAction'] != 0) {
            $profileAction = $targetVariable['VariableCustomAction'];
        } else {
            $profileAction = $targetVariable['VariableAction'];
        }
        if ($profileAction < 10000) {
            return false;
        }
        
        if (IPS_InstanceExists($profileAction)) {
            IPS_RunScriptText('IPS_RequestAction(' . var_export($profileAction, true) . ', ' . var_export(IPS_GetObject($variableID)['ObjectIdent'], true) . ', ' . var_export($value, true) . ');');
        } elseif (IPS_ScriptExists($profileAction)) {
            IPS_RunScriptEx($profileAction, ['VARIABLE' => $variableID, 'VALUE' => $value, 'SENDER' => 'VoiceControl']);
        } else {
            return false;
        }
        return true;
    }
}
?>