<?php
namespace CsnSocial\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class AddGroupFilter extends InputFilter
{
	public function __construct($sm)
	{
		$this->add(array(
			'name'     => 'group',
			'required' => true,
			'filters'  => array(
		    	array('name' => 'StripTags'),
		    	array('name' => 'StringTrim'),
		    ),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 1,
						'max'      => 50,
					),
				),
			), 
		));	
	}
}
