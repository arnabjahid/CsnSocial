<?php
/**
 * coolcsn CsnSocial
 *
 * @link https://github.com/coolcsn/CsnSocial for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 */

namespace CsnSocial\Entity;

use Doctrine\ORM\Mapping as ORM;
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * Group
 *
 * @ORM\Table(name="`group`")
 * @ORM\Entity
 * @Annotation\Name("Group")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Group {

	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     */
    protected $id;

	/**
     *
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     * @Annotation\Exclude()
     */
	protected $owner;
	
	/**
	 * 
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=false)
     * @Annotation\Exclude()
     */
	protected $member;
	
	/**
     *
     * @ORM\ManyToOne(targetEntity="CsnCms\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     * @Annotation\Exclude()
     */
	protected $category;
	
	/**
     * Set owner
     *
     * @param  CsnUser\Entity\User $id
     * @return Owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return CsnUser\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
    
    /**
     * Set member
     *
     * @param  CsnUser\Entity\User $id
     * @return Member
     */
    public function setMember($member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return CsnUser\Entity\User
     */
    public function getMember()
    {
        return $this->member;
    }
    
    /**
     * Set category
     *
     * @param  CsnCms\Entity\Category $id
     * @return Category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return CsnCms\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Get Id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}