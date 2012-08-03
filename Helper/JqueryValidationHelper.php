<?php
/**
 * Author : Xttechnologies
 * Created : 3 Aug, 2012
 * JqueryValidation helper
 *
 * @package       app.View.Helper
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
		
		foreach($rules as $rule){
			foreach($rule as $model => $fields){
				foreach($fields as $fieldName => $rules){
					$ruleScript .= "'data[{$model}][{$fieldName}]':" ;
					$ruleScript .= $this->__convertRule($rules);					
				}
			}
		}
		
		$validationScript = "$(function(){
			$('form').validate({
				rules: {" . $ruleScript . "}
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
}
