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
        	$message = $this->getTranslator()->translate('You don\'t have any gorups yet');
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
			//Delete all comments first
			/*if($comments = $this->getEntityManager()->getRepository('CsnCms\Entity\Comment')->findBy(array('article' => $id, ))){
				foreach($comments as $comment){
				  	$this->getEntityManager()->remove($comment);
		        	$this->getEntityManager()->flush();
				  }
			}*/
			//Delete the article
			$this->getEntityManager()->remove($group);
			$this->getEntityManager()->flush();	
			return $this->redirect()->toRoute('groups');
		}else {
			return $this->redirect()->toRoute('groups');
		}
    	//return new ViewModel(array('form' => $form, 'groups' => $groups, 'message' => $message));
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
