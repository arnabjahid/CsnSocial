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
     * Category index action
     *
     * ???
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
			//$form->setInputFilter(new AddEventFilter($this->getServiceLocator()));
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$data = $form->getData();
                
                $group->setName($data['group']);  
				//$this->prepareData($article, $data, false);
				$this->getEntityManager()->persist($group);
				$this->getEntityManager()->flush();
				return $this->redirect()->toRoute('groups');
			}
		}
        /*
        $dqlArticles = "SELECT a, u, l, c FROM CsnCms\Entity\Article a LEFT JOIN a.author u LEFT JOIN a.language l LEFT JOIN a.categories c   ORDER BY a.created DESC"; 
        $query = $this->getEntityManager()->createQuery($dqlArticles);
        //$query->setMaxResults(30);
        $articles = $query->getResult();
        
        $dqlMyFriends = "SELECT u, f FROM CsnUser\Entity\User u LEFT JOIN u.friendsWithMe f WHERE f.id = ".$user->getId(); 

        $query = $this->getEntityManager()->createQuery($dqlMyFriends);
        $query->setMaxResults(30);
        $myFriends = $query->getResult();
        
        //print $query->getSQL();
        
        $dqlFriendsWithMe = "SELECT u, f FROM CsnUser\Entity\User u LEFT JOIN u.myFriends f WHERE f.id = ".$user->getId(); 

        $query = $this->getEntityManager()->createQuery($dqlFriendsWithMe);
        $query->setMaxResults(30);
        $friendsWithMe = $query->getResult();
		*/
        return new ViewModel(array('user' => $user, 'form' => $form, 'articles' => $articles, 'myFriends' => $myFriends, 'friendsWithMe' => $friendsWithMe));
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
