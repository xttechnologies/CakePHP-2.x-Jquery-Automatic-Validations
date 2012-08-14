CakePHP-2.x-Jquery-Automatic-Validations
========================================

Version 1.1
-----------

It will detect the messages from models for validations if defined else it shows its own messages.

Version 1.0
-----------
CakePHP 2.x Automatic Jquery Validations with Jquery Validator

The JqueryValidationHelper uses jQuery Validation Plugin.

Steps to use the helper. 

1. Place JqueryValidationHelper file in app/View/Helper

2. enable the helper in AppController.php or in the Controller you want to use
	public $helpers = array('JqueryValidation');

It will auto detect the validation on the basis of models and apply it to your form.
Enjoy the AutoMatic Validator.
