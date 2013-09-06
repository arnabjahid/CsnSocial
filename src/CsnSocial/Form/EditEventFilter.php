<?php
namespace CsnSocial\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class EditEventFilter extends InputFilter
{
	public function __construct($sm)
	{
        $this->add(array(
			'name'     => 'event',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags',
					'options' => array(
						'allowTags'		=> array('br', 'p', 'a', 'b', 'i', 'u','center', 'bdo', 'del', 'sup', 'sub', 'img', 'iframe'),
						'allowAttribs'	=> array('href','target', 'dir', 'alt', 'src', 'width', 'height', 'frameborder', 'allowfullscreen', 'webkitallowfullscreen', 'mozallowfullscreen'),
					)
				),
				
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
		$this->add(array(
			'name'     => 'title',
			'required' => false,
			'filters'  => array(
				array('name' => 'StripTags'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max'      => 150,
					),
				),
			), 
		));	
	}
}
