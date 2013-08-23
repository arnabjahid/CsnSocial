<?php

namespace CsnSocial\Entity;

use CsnUser\Entity\User as CsnUserEntity; // Entity from module CsnUser https://github.com/coolcsn/CsnUser/blob/master/src/CsnUser/Entity/User.php

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

class User extends CsnUserEntity
{
	
}