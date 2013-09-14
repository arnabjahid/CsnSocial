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

use CsnSocial\Form\FindPersonForm;

/**
 * Person Controller
 */
class PersonController extends AbstractActionController
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
     * Find person action
     *
     * Find person name in the database using doctrine
     *
     */
	public function findPersonAction() {
	
    	if (!$user = $this->identity()) {
			return $this->redirect()->toRoute('login', array('controller' => 'index', 'action' => 'login'));
		}

		$findPersonForm = new FindPersonForm();
		$request = $this->getRequest();
		$message = null;

		if ($request->isPost()) {

			$findPersonForm->setData($request->getPost());
			if ($findPersonForm->isValid()) {
				$data = $findPersonForm->getData();
				
				if($data['search'] !== '') {
					$search = '%'.$data['search'].'%';		
				
					$dqlSearch = "SELECT u.id, u.firstName, u.lastName FROM CsnUser\Entity\User u WHERE CONCAT(u.firstName, ' ', u.lastName) LIKE :string";
				
					$query = $this->getEntityManager()->createQuery($dqlSearch);
					$query->setParameter('string', $search);
		   			$query->setMaxResults(100);
		    		$resultSearch = $query->getResult();
		    		
		    		// remove current user from result to prevent adding themself
		    		$user_id = $this->identity()->getId();
					foreach($resultSearch as $k) {
						if(in_array($user_id, $k)) {
							$key = array_search($k, $resultSearch);
							unset($resultSearch[$key]);
						}
					}		
					if($resultSearch == null){		
						$message = $this->getTranslator()->translate('Person with %s in their name cannot be found!');					
					}
					
				}else{
					$message = $this->getTranslator()->translate('Please enter a person name!');
				}
			}
        }
        
    	return new ViewModel(
			array(
				'resultSearch' => $resultSearch,
				'search' => $data['search'], 
				'findPersonForm' => $findPersonForm, 
				'message' => $message
			)
    	);
    }
    
    /**
     * Add person action
     *
     * Add person to user friends list
     *
     */
    public function addPersonAction()
    {
    	$id = $this->params()->fromRoute('id');
    	if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
    	$user = $this->identity();

		$message = null;

		if($adduser = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('id' => $id))){
			foreach($user->getMyFriends() as $friend){
				if($friend->getId() == $adduser->getId()){
					$message = $friend->getFirstName().' '.$friend->getLastName().$this->getTranslator()->translate(' is already in your list!');
					$error = true;
					break;
				}
			}
			if(!$error){
				$user->addMyFriend($adduser);
				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();
				$message = $adduser->getFirstName().' '.$adduser->getLastName().$this->getTranslator()->translate(' successfully added!');
			}
		}
    	return new ViewModel(array('person' => $adduser, 'form' => $form, 'message' => $message));
    }
    
    /**
     * Delete person action
     *
     * Delete person from user friends list
     *
     */
    public function deletePersonAction() {
    	
    	$id = $this->params()->fromRoute('id');
    	
        if (!$id) {
        	
            return $this->redirect()->toRoute('social');
            
        }
        
        $user = $this->identity();
        
        if($userToDel = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('id' => $id))) {
        
        	foreach($user->getMyFriends() as $friend){
        	
				if($friend->getId() == $userToDel->getId()){

					$user->removeMyFriend($userToDel);
				    $this->getEntityManager()->persist($user);
				    $this->getEntityManager()->flush();
				    return $this->redirect()->toRoute('social');
				    
				}
				
			}
            			
        }else {
        
            return $this->redirect()->toRoute('social');
            
        }	 
    }
    
    /**
     * @return EntityManager
     */
	public function getEntityManager()
	{
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