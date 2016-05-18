<?php

namespace Wame\AutocompleteFormControl\Controls;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Container;
use Doctrine\ORM\PersistentCollection;
use Kdyby\Doctrine\Collections\ReadOnlyCollectionWrapper;

class AutocompleteFormControl extends BaseControl
{
	/** @var bool */
	private static $registered = false;

    /** @var string */
    private $labelName;
    
    /** @var string */
    private $source;
    
    /** @var array */
    private $options;
    
    /** @var string */
    private $autocompleteValue;
    
    /** @var string */
    private $defaultValue;
  
    
	public function __construct($label = null, $source = null, array $config = []) 
    {
		parent::__construct($label);

        $this->labelName = $label;
        $this->source = $source;
        $this->options = $config;
	}
 
    
    /**
     * Set options
     * 
     * @param array $options
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }

        return $this;
    }
	
    
    /**
     * Set minimum length
     * 
     * @param int $minLength
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setMinLength($minLength)
    {
        $this->options['minLength'] = $minLength;
        
        return $this;
    }
	
    
    /**
     * Set limit
     * 
     * @param int $limit
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setLimit($limit)
    {
        $this->options['limit'] = $limit;
        
        return $this;
    }
	
    
    /**
     * Set offset
     * 
     * @param int $offset
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setOffset($offset)
    {
        $this->options['offset'] = $offset;
        
        return $this;
    }
    
	
    /**
     * Set value
     * 
     * @param string $value
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setFieldValue($value)
    {
        $this->options['fieldValue'] = $value;
        
        return $this;
    }
    
	
    /**
     * Set label
     * 
     * @param string $label
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setFieldLabel($label)
    {
        $this->options['fieldLabel'] = $label;
        
        return $this;
    }
    
	
    /**
     * Set search
     * 
     * @param string $search
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setFieldSearch($search)
    {
        $this->options['fieldSearch'] = $search;
        
        return $this;
    }
    
	
    /**
     * Set CSS class
     * 
     * @param string $class
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setClass(array $class)
    {
        $this->options['class'] = $class;
        
        return $this;
    }
    
	
    /**
     * Set default value
     * 
     * @param mixed $value
     * @return \Wame\AutocompleteFormControl\Controls\AutocompleteFormControl
     */
    public function setValue($value)
    {
        if (!is_null($value)) {
            if (is_array($value) || $value instanceof ReadOnlyCollectionWrapper || $value instanceof PersistentCollection) {
                $this->autocompleteValue = $this->prepareValue($value);
            } else {
                $this->autocompleteValue = $value;
            }
        }
        
        $this->defaultValue = $this->autocompleteValue;
    }
    
    
    /**
     * Prepare value
     * 
     * @param mixed $values
     * @return string
     */
    private function prepareValue($values = [])
    {
        $i = 0;
        $fieldValue = $this->options['fieldValue'];
        $delimeter = $this->options['delimeter'];
        $return = '';
        
        foreach ($values as $key => $value) {
            $i++;
            $return .= isset($value->$fieldValue) ? $value->$fieldValue : $key;
            
            if ($i < count($values)) {
                $return .= $delimeter;
            }
        }
        
        return $return;
    }
    
    
    /**
     * Prepare input
     * 
     * @return \Nette\Utils\Html
     */
    public function getControl()
	{
		parent::getControl();

		$this->setOption('rendered', true);

		$control = clone $this->control;

        return $control->addAttributes([
            'id' => $this->getHtmlId(),
            'type' => 'text',
            'name' => $this->getHtmlName(),
			'class' => array(isset($this->options['class']) ? $this->options['class'] : 'autocomplete' . ' form-control'),
			'data-source' => $this->source,
			'data-options' => $this->options,
            'value' => $this->defaultValue
        ]);	
	}

	
	public static function register($method = 'addAutocomplete', $config = [])
	{
		if (static::$registered) {
			throw new Nette\InvalidStateException(_('Autocomplete form control already registered.'));
		}
		
		static::$registered = true;
		
		$class = function_exists('get_called_class') ? get_called_class() : __CLASS__;
		
		Container::extensionMethod(
			$method, function (Container $container, $name, $label = null, $source = null, $options = null) use ($config, $class) 
            {
				$component[$name] = new $class($label, $source, is_array($options) ? array_replace($config, $options) : $config);
				
				$container->addComponent($component[$name], $name);
                
				return $component[$name];
			}
		);
	}
	
}