<?php
namespace CsnSocial\Form;

use Zend\Form\Form;

class SearchFrom extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('index');
        $this->setAttribute('method', 'post');
		
		$this->add(array(
            'name' => 'search',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Type person username, display name or email...',
                'size' => 40
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Find',
                'class' => 'btn btn-success btn-lg',
            ),
        )); 
    }
}
