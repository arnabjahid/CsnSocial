<?php
namespace CsnSocial\Form;

use Zend\Form\Form;

class AddGroupForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('index');
        $this->setAttribute('method', 'post');
		
		$this->add(array(
            'name' => 'group',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Group name',
                'size' => 20
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Add group',
                'class' => 'btn btn-success btn-lg',
            ),
        )); 
    }
}
