<?php

namespace Wame\AutocompleteFormControl\DI;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

class AutocompleteFormExtension extends CompilerExtension
{
    private $defaults = [
        'multiple' => false,
        'minLength' => 3,
        'limit' => null,
        'offset' => null,
        'delimeter' => ',',
		'columns' => ['langs.title'],
		'select' => '*',
        'fieldValue' => 'id',
        'fieldLabel' => 'title',
        'fieldSearch' => 'title'
    ];
    
    
	/**
	 * @param ClassType $class
	 */
	public function afterCompile(ClassType $class)
	{
		parent::afterCompile($class);
        
		$initialize = $class->methods['initialize'];
        $config = $this->getConfig($this->defaults);
		$initialize->addBody('\Wame\AutocompleteFormControl\Controls\AutocompleteFormControl::register(?, ?);', ['addAutocomplete', $config]);
	}

}