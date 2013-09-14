<?php
namespace CsnSocial\Form;

use Zend\Form\Form;

class AddCommentForm extends Form
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
                'size' => '30',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
        $this->add(array(
            'name' => 'text',
            'attributes' => array(
                'type'  => 'textarea',
                'rows'  => '2',
                'cols'  => '30',
                'placeholder' =>'Comment here...',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Add comment',
                'class' => 'btn btn-success btn-lg',
            ),
        )); 
    }
}
