<?php
declare(strict_types=1);

trait HelperSwitchDevice {
    private static function getSwitchCompatibility($variableID, $mapping){
        if (!IPS_VariableExists($variableID)) {
            return 'Missing variable';
        }
        $targetVariable = IPS_GetVariable($variableID);
        if ($targetVariable['VariableType'] != 0 /* Boolean */) {
            return 'Bool required';
        }
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
    private static function getSwitchValue($variableID){
        $targetVariable = IPS_GetVariable($variableID);
        if ($targetVariable['VariableCustomProfile'] != '') {
            $profileName = $targetVariable['VariableCustomProfile'];
        } else {
            $profileName = $targetVariable['VariableProfile'];
        }
        $value = GetValue($variableID);
        // Revert value for reversed profile
        if (preg_match('/\.Reversed$/', $profileName)) {
            $value = !$value;
        }
		
        return $value;
    }
	
    private static function switchDevice($variableID, $value){
		IPS_LogMessage("switchDevice","Switching device to ".(string)$value);
		
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
        if ($targetVariable['VariableType'] == 0 /* Boolean */) {
            $value = boolval($value);
        } else {
            return false;
        }
		
		IPS_LogMessage("switchDevice","switching device to ".(string)$value);
		
        // Revert value for reversed profile
        if (preg_match('/\.Reversed$/', $profileName)) {
            $value = !$value;
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