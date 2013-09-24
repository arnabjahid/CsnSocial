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

namespace CsnSocial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use CsnSocial\Form\AddGroupForm;
use CsnSocial\Form\AddGroupFilter;

use CsnCms\Entity\Category;
use CsnSocial\Entity\Group;

/**
 * Category Controller
 */
class GroupController extends AbstractActionController
{
    /**             
	 * @var Doctrine\ORM\EntityManager
	 */                
	protected $em;
	
	/**             
	 * @var Zend translator
	 */  
	protected $translator;
    
    /**
     * Group index action
     *
     * View and add groups
     *
     */
    public function indexAction() {
    
    	if (!$user = $this->identity()) {
			return $this->redirect()->toRoute('login', array('controller' => 'index', 'action' => 'login'));
		}
		
		$user = $this->identity();

		$group = new Category;
		$form = new AddGroupForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter(new AddGroupFilter($this->getServiceLocator()));
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$data = $form->getData();
                if(!$groupExist = $this->getEntityManager()->getRepository('CsnCms\Entity\Category')->findOneBy(array('name' => $data['group'], 'user' => $user->getId()))) {
		            $group->setUser($user);
		            $group->setName($data['group']);  

					$this->getEntityManager()->persist($group);
					$this->getEntityManager()->flush();
					return $this->redirect()->toRoute('groups');
				}else{
					$message = $this->getTranslator()->translate('This group already exist!');
					return new ViewModel(array('message' => $message, 'form' => $form));
				}
			}
		}
        if($groups = $this->getEntityManager()->getRepository('CsnCms\Entity\Category')->findBy(array('user' => $user->getId()))){
        	
        }else{
        	$message = $this->getTranslator()->translate('You don\'t have any groups yet');
        }
        if(count($groups) == 1){
        	foreach($groups as $group){
        		if($group->getName() === 'No category'){
        			$message = $this->getTranslator()->translate('You don\'t have any groups yet');
        		}
        	}
        }

        return new ViewModel(array('form' => $form, 'groups' => $groups, 'message' => $message));
    }
    
    /**
     * Group edit action
     *
     * Rename groups
     *
     */
    public function editGroupAction() {
    
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();

		if($group = $this->getEntityManager()->getRepository('CsnCms\Entity\Category')->findOneBy(array('id' => $id, 'user' => $user->getId()))) {

			$form = new AddGroupForm();
			$data = array(
				'group'    => $group->getName()
			);
			$form->setData($data);
			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setInputFilter(new AddGroupFilter($this->getServiceLocator()));
				$form->setData($request->getPost());
				if ($form->isValid()) {
					$data = $form->getData();
				    if(!$groupExist = $this->getEntityManager()->getRepository('CsnCms\Entity\Category')->findOneBy(array('name' => $data['group'], 'user' => $user->getId()))) {
						$group->setName($data['group']);
						$this->getEntityManager()->persist($group);
						$this->getEntityManager()->flush();
						$message = $this->getTranslator()->translate('Update successful!');
					}else{
						$message = $this->getTranslator()->translate('This group already exist!');
					}
					
					return new ViewModel(array('form' => $form, 'message' => $message));
				}
			}
             
			return new ViewModel(array('group' => $group, 'form' => $form));
		}else {
			return $this->redirect()->toRoute('social');
		}

    }
    
    /**
     * Group delete action
     *
     * Delete groups
     *
     */
    public function deleteGroupAction() {
    
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
		if($group = $this->getEntityManager()->getRepository('CsnCms\Entity\Category')->findOneBy(array('id' => $id, 'user' => $user->getId()))) {
			//Delete all members of this group
			if($members = $this->getEntityManager()->getRepository('CsnSocial\Entity\Group')->findBy(array('owner' => $user->getId(), 'category' => $id))){
				foreach($members as $member){
				  	$this->getEntityManager()->remove($member);
		        	$this->getEntityManager()->flush();
				  }
			}
			
			// Change category of all articles in this group to "No category"
			if($articles = $this->getEntityManager()->getRepository('CsnCms\Entity\Article')->findBy(array('author' => $user->getId()))){
				foreach($articles as $article){
					if(count($article->getCategories()) == 1){
						
						foreach($article->getCategories() as $category){
							
						  	if($category->getId() == $id){
						  		
						  		$article->removeCategory($category);
						  		
						  		// Check if category with name "No category" exist
						  		if($catExist = $this->getEntityManager()->getRepository('CsnCms\Entity\Category')->findOneBy(array('user' => $user->getId(), 'name' => 'No category'))) {
						  			$article->addCategory($catExist);
						  		}else {
						  		
						  			$cat = new \CsnCms\Entity\Category();
							  		$cat->setUser($user);
							  		$cat->setName('No category');
							  		$this->getEntityManager()->persist($cat);
							  		$this->getEntityManager()->flush();
									$article->addCategory($cat);

						  		}
						  		
								$this->getEntityManager()->persist($article);
								$this->getEntityManager()->flush();
						  	}
						}
					}
				}
			}
			
			//Delete the group
			$this->getEntityManager()->remove($group);
			$this->getEntityManager()->flush();	
			return $this->redirect()->toRoute('groups');
		}else {
			return $this->redirect()->toRoute('groups');
		}
    }
    
    /**
     * viewMembers action
     *
     * View members of group by given owner id
     *
     */
    public function viewMembersAction() {
    
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
    
    	if (!$user = $this->identity()) {
			return $this->redirect()->toRoute('login', array('controller' => 'index', 'action' => 'login'));
		}
		
		$user = $this->identity();
        
        $dqlMyFriends = "SELECT u, f FROM CsnUser\Entity\User u LEFT JOIN u.friendsWithMe f WHERE f.id = ".$user->getId(); 

        $query = $this->getEntityManager()->createQuery($dqlMyFriends);
        $query->setMaxResults(30);
        $friends = $query->getResult();
        
        //print $query->getSQL();
        
        if(!$friends){
        	$message = $this->getTranslator()->translate('You don\'t have any friends yet');
        }
        
        $group = $this->getEntityManager()->getRepository('CsnCms\Entity\Category')->findOneBy(array('id' => $id, 'user' => $user->getId()));
        $groupId = $group->getId();
		$groupName = $group->getName();
		
		$friendsInGroup = $this->getEntityManager()->getRepository('CsnSocial\Entity\Group')->findBy(array('owner' => $user->getId(), 'category' => $id));
		if(!$friendsInGroup){
			$message2 = $this->getTranslator()->translate('This group is empty');
		}
        return new ViewModel(array(
										'groupId' => $groupId,
										'groupName' => $groupName,
										'friends' => $friends,
										'friendsInGroup' => $friendsInGroup,
										'message' => $message,
										'message2' => $message2
									)
								);
    }
    
    /**
     * addMember action
     *
     * Add member to group by given owner id
     *
     */
    public function addMemberAction() {
    
    	$id = $this->params()->fromRoute('id');
    	$id2 = $this->params()->fromRoute('id2');
        if (!$id || !$id2) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        if(!$groupExist = $this->getEntityManager()->getRepository('CsnSocial\Entity\Group')->
        	findOneBy(array('owner' => $user->getId(), 'member' => $id2, 'category' => $id))) {
        	
		    $member = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('id' => $id2));
			$category = $this->getEntityManager()->getRepository('CsnCms\Entity\Category')->findOneBy(array('id' => $id));
		    
			$group = new Group();
			$group->setOwner($user);
			$group->setCategory($category); 
			$group->setMember($member); 

			$this->getEntityManager()->persist($group);
			$this->getEntityManager()->flush();
			return $this->redirect()->toRoute('view-members/default', array('id'=>$id));
		}else {
			return $this->redirect()->toRoute('view-members/default', array('id'=>$id));
		}
    }
    
    /**
     * removeMember action
     *
     * Remove member from a group by given owner id
     *
     */
    public function removeMemberAction() {
    
    	$id = $this->params()->fromRoute('id');
    	$id2 = $this->params()->fromRoute('id2');
        if (!$id || !$id2) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        if($memberToDel = $this->getEntityManager()->getRepository('CsnSocial\Entity\Group')->
        	findOneBy(array('owner' => $user->getId(), 'member' => $id2, 'category' => $id))) {

			$this->getEntityManager()->remove($memberToDel);
			$this->getEntityManager()->flush();
			return $this->redirect()->toRoute('view-members/default', array('id'=>$id));
		}else {
			return $this->redirect()->toRoute('view-members/default', array('id'=>$id));
		}
    }
    
    /**
     * @return EntityManager
     */
	public function getEntityManager() {
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		}
		return $this->em;
	}

	/**
     * @return Translator
     */
	public function getTranslator() {
		if (null === $this->translator) {
			$this->translator = $this->getServiceLocator()->get('translator');
		}
		return $this->translator;
	}
}
