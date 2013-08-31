<?php
namespace CsnSocial\Form;

use Zend\Form\Form;

class AddPersonForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('index');
        $this->setAttribute('method', 'post');
		
		$this->add(array(
            'name' => 'addPerson',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Add this person',
                'class' => 'btn btn-success',
            ),
        )); 
    }
}
