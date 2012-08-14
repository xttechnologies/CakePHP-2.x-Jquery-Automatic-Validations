<?php
/**
 * Author : XT Technologies
 * Created : 3 Aug, 2012
 * JqueryValidation helper
 *
 * @package app.View.Helper
 * required Jquery 1.7.2
 * Jquery Validation files http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * filename :
 * 	jquery.validate.min.js
 *	additional-methods.min
 */
class JqueryValidationHelper extends AppHelper {
	
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
	}
	
	public $helpers = array('Html');
	
	/*
	 *function getModels to get the model info for the view 
	 */
	public function getModels(){
		$models = array();
		foreach($this->request->params['models'] as $modelName => $model){
			$plugin = $model['plugin'];
			$plugin .= ($plugin) ? '.' : null;
			
			$models[] = array(
				'class' => $plugin . $model['className'],
				'alias' => $modelName
			);
		}
		
		return $models;
	}
	
	/*
	 * getModelValidationRules to get validation info according to given models  
	 */
	public function getModelValidationRules($models = array()){
		$validationRules = array();
		foreach($models as $model){
			$object = ClassRegistry::init($model);
			$validationRules[] = array($model['alias'] => $object->validate);
		}
		
		return $validationRules;
	}
	
	/*
	 * 
	 */
	public function bindValidations(){
		$rules = $this->getModelValidationRules($this->getModels());
		
		$ruleScript = '';
		$messageScript ='';
		
		foreach($rules as $rule){
			foreach($rule as $model => $fields){
				foreach($fields as $fieldName => $rules){
					$ruleScript .= "'data[{$model}][{$fieldName}]':" ;
					$ruleScript .= $this->__convertRule($rules);

					$messageScript .= "'data[{$model}][{$fieldName}]':";
					$messageScript .= $this->__convertMessage($rules);
				}
			}
		}
		
		$validationScript = "$(function(){
			$('form').validate({
				rules: {" . $ruleScript . "},
				messages: {" . $messageScript ."} 
			});	
		});";
		return $this->Html->scriptBlock($validationScript);
	}
	
	/*
	 * 
	 */
	function __convertRule($rules = array()) {
		
		$fieldRulesScript = '{';
		
		foreach($rules as $ruleDetails){
			switch ($ruleDetails['rule'][0]) {
				case 'between':
					if(isset($ruleDetails['rule'][1]) && isset($ruleDetails['rule'][2]))
						$fieldRulesScript .= "range: [{$ruleDetails['rule'][1]}, {$ruleDetails['rule'][2]}],";
					break;
				case 'notempty':
					$fieldRulesScript .= "required: true,";
					break;
				case 'minlength':
					if(isset($ruleDetails['rule'][1]))
						$fieldRulesScript .= "minlength: {$ruleDetails['rule'][1]},";
					break;
				case 'date':
					$fieldRulesScript .= "date: true,";
					break;
				case 'ip':
					$fieldRulesScript .= "ipv4: true,";
					break;
				case 'money':
					$fieldRulesScript .= "digits: true,";
					break;
				case 'url':
					$fieldRulesScript .= "url: true,";
					break;
				case 'email':
					$fieldRulesScript .= "email: true,";
					break;
				case 'phone':
					$fieldRulesScript .= "number: true,";
					break;
			}
		}
		$fieldRulesScript .= '},';
		return $fieldRulesScript;
	}
	
	/*
	 *
	*/
	function __convertMessage($rules = array()) {
	
		$fieldMessageScript = '{';
		
		foreach($rules as $ruleDetails){
			switch ($ruleDetails['rule'][0]) {
				case 'between':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'range: "'. $ruleDetails['message'] .'",';
					break;
				case 'notempty':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'required: "'. $ruleDetails['message'] .'",';
					break;
				case 'minlength':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'minlength: "'. $ruleDetails['message'] .'",';
					break;
				case 'date':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'date: "'. $ruleDetails['message'] .'",';
					break;
				case 'ip':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'ipv4: "'. $ruleDetails['message'] .'",';
					break;
				case 'money':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'digits: "'. $ruleDetails['message'] .'",';
					break;
				case 'url':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'url: "'. $ruleDetails['message'] .'",';
					break;
				case 'email':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'email: "'. $ruleDetails['message'] .'",';
					break;
				case 'phone':
					if(isset($ruleDetails['message']))
						$fieldMessageScript .= 'number: "'. $ruleDetails['message'] .'",';
					break;
			}
		}
		$fieldMessageScript .= '},';
		return $fieldMessageScript;
	}
}
