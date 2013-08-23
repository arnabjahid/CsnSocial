<?php
namespace CsnSocial\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class AddEventFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
			'name'     => 'event',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 1,
					),
				),
			), 
		));	
	}
}
