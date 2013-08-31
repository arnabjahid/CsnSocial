<?php
namespace CsnSocial\Form;

use Zend\Form\Form;

class AddEventForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('index');
        $this->setAttribute('method', 'post');
		
		$this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Title',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
        $this->add(array(
            'name' => 'event',
            'attributes' => array(
                'type'  => 'textarea',
                'rows'  => '3',
                'cols'  => '50',
                'placeholder' =>'Tell to your followers what\'s happening...',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
        $this->add(array(
        	'name' => 'followers',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'multiple' => 'multiple',
                'options' => array(
		                '0' => 'All',
		                '1' => 'group1',
		                '2' => 'group2',
		                '3' => 'group3',
				        ),
		        ),
            'options' => array(
                'label' => ' ',
            ),
        )); 
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Share',
                'class' => 'btn btn-success btn-lg',
            ),
        )); 
    }
}
