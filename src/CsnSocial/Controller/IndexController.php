<?php
namespace CsnSocial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use CsnSocial\Form\AddEventForm;
use CsnSocial\Form\AddEventFilter;

class IndexController extends AbstractActionController
{
    /**             
	 * @var Doctrine\ORM\EntityManager
	 */                
	protected $em;
    
    public function indexAction()
    {
    	// Uncomment these lines if don't use https://github.com/coolcsn/CsnAclNavigation
		///*
		if (!$user = $this->identity()) {
			return $this->redirect()->toRoute('login', array('controller' => 'index', 'action' => 'login'));
		}
		//*/
		
		$user = $this->identity();
		
		$form = new AddEventForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
                    $form->setInputFilter(new AddEventFilter($this->getServiceLocator()));
					$form->setData($request->getPost());
                    if ($form->isValid()) {
                        $data = $form->getData();
                        //$event = $data['event'];

						//$user = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('User' => $user->getId()));
						//echo $user->getMyFriends();
						//$friends = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findBy(array('myFriends' => $user->getId()));
						//echo $users->getMyFriends();
						//foreach ($user->getMyFriends() AS $friend) {
						//	echo $friend->getMyFriends() . "\n\n";
						//}
						//echo '<pre>';
						//print_r($user);
						//echo '</pre>';
						//$user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
						//$this->flashMessenger()->addMessage($user->getEmail());
						//$this->getEntityManager()->persist($user);
						//$this->getEntityManager()->flush();
						echo 'Post!'.$user->getId();
                   }
            }
		
        return new ViewModel(array('user' => $user, 'form' => $form));
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

}
