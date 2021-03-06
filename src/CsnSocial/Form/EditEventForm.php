<?php
namespace CsnSocial\Form;

use Zend\Form\Form;

class EditEventForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('index');
        $this->setAttribute('method', 'post');
		
		$this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Title (optional)',
                'size' => '50',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
        $this->add(array(
            'name' => 'event',
            'attributes' => array(
                'type'  => 'textarea',
                'rows'  => '2',
                'cols'  => '50',
                'placeholder' =>'Tell to your followers what\'s happening...',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Edit',
                'class' => 'btn btn-success btn-lg',
            ),
        )); 
    }
}
