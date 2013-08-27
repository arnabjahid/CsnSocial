<?php

namespace CsnSocial\Entity;

use CsnUser\Entity\User as CsnUserEntity; // Entity from module CsnUser https://github.com/coolcsn/CsnUser/blob/master/src/CsnUser/Entity/User.php

use Doctrine\ORM\Mapping as ORM;//MappedSuperclass (delete this comment)

use Zend\Form\Annotation;

/**
 * Default implementation of User
 *
 * Table(name="user")
 * MappedSuperclass(repositoryClass="CsnUser\Entity\Repository\UserRepository")
 * @Annotation\Name("User")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class User extends CsnUserEntity
{
	 /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     */
    private $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friend",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_id", referencedColumnName="id")}
     *      )
     */
    private $myFriends;
}
