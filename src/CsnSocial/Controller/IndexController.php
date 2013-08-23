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
		if (!$user = $this->identity()) {
			return $this->redirect()->toRoute('login', array('controller' => 'index', 'action' => 'login'));
		}
		$form = new AddEventForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
                    $form->setInputFilter(new AddEventFilter($this->getServiceLocator()));
					$form->setData($request->getPost());
                    if ($form->isValid()) {
                        $data = $form->getData();
                        $event = $data['event'];

						//$user = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('username' => $user->getUsername()));
						//$user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
						//$this->flashMessenger()->addMessage($user->getEmail());
						//$this->getEntityManager()->persist($user);
						//$this->getEntityManager()->flush();
						echo 'Post!';
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