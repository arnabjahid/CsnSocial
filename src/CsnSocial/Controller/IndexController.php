<?php
namespace CsnSocial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use CsnSocial\Form\AddEventForm;
use CsnSocial\Form\AddEventFilter;

use CsnSocial\Form\SearchFrom;
use CsnSocial\Form\AddPersonForm;

use CsnCms\Entity\Article;
use CsnUser\Entity\User;

class IndexController extends AbstractActionController
{
    /**             
	 * @var Doctrine\ORM\EntityManager
	 */                
	protected $em;
	
	/**             
	 * @var Intro text lenght
	 */ 
	protected $introTextLenght = 150;
    
    public function indexAction()
    {

		if (!$user = $this->identity()) {
			return $this->redirect()->toRoute('login', array('controller' => 'index', 'action' => 'login'));
		}
		
		$user = $this->identity();
		
		$article = new Article;
		$form = new AddEventForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
                    $form->setInputFilter(new AddEventFilter($this->getServiceLocator()));
					$form->setData($request->getPost());
                    if ($form->isValid()) {
                        $data = $form->getData();
                        
						$this->prepareData($article, $data);
		                $this->getEntityManager()->persist($article);
		                $this->getEntityManager()->flush();
		                return $this->redirect()->toRoute('social');
                   }
        }
        
        $dqlArticles = "SELECT a, u, l, c FROM CsnCms\Entity\Article a LEFT JOIN a.author u LEFT JOIN a.language l LEFT JOIN a.categories c   ORDER BY a.created DESC"; 
        $query = $this->getEntityManager()->createQuery($dqlArticles);
        $query->setMaxResults(30);
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
		
        return new ViewModel(array('user' => $user, 'form' => $form, 'articles' => $articles, 'myFriends' => $myFriends, 'friendsWithMe' => $friendsWithMe));
    }
    
    public function findPeopleAction()
    {
    	if (!$user = $this->identity()) {
			return $this->redirect()->toRoute('login', array('controller' => 'index', 'action' => 'login'));
		}
		
		$user = $this->identity();
		//$article = new Article;
		$form = new SearchFrom();
		$request = $this->getRequest();
		$message = null;
		$formAddPerson = null;
		if ($request->isPost()) {
			//$form->setInputFilter(new AddEventFilter($this->getServiceLocator()));
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$data = $form->getData();
				//$this->prepareData($article, $data);
				//$this->getEntityManager()->persist($article);
				//$this->getEntityManager()->flush();
				if(($person = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('displayName' => $data['search']))) || 
				($person = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('username' => $data['search']))) ||
				($person = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $data['search'])))){
					$message = $person->getDisplayName().' was found.';
					$formAddPerson = new AddPersonForm();
					$formAddPerson->get('addPerson')->setValue($person->getId());
				}else{
					$message = 'person with name "'.$data['search'].'" can\'t be found around';
				}
			}
        }
        
    	return new ViewModel(array('person' => $person, 'form' => $form, 'formAddPerson' => $formAddPerson, 'message' => $message));
    }
    
    public function addPersonAction()
    {
    	$id = $this->params()->fromRoute('id');
    
    	$user = $this->identity();
		$request = $this->getRequest();
		$message = null;
		if ($request->isPost()) {
			$data = $request->getPost();
			$adduser = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('id' => $data['addPerson']));
			foreach($user->getMyFriends() as $friend){
				if($friend->getId() == $adduser->getId()){
					$message = $friend->getDisplayName(). ' is already in your list!';
					$error = true;
					break;
				}
			}
			if($adduser->getId() == $user->getId()){
					$message = $user->getDisplayName(). ', you can\'t add yourself!';
					$error = true;
			}
			if(!$error){
				$user->addMyFriend($adduser);
				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();
				$message = $adduser->getDisplayName().' successfully added to your list!';
			}

		}else if ($id) {
            $adduser = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('id' => $id));
            $user->addMyFriend($adduser);
			$this->getEntityManager()->persist($user);
			$this->getEntityManager()->flush();
			//$message = $adduser->getDisplayName().' successfully added to your list!';
			$this->redirect()->toRoute('social', array('controller' => 'index', 'action' => 'index'));
            
        }else{
			$this->redirect()->toRoute('social', array('controller' => 'index', 'action' => 'index'));
		}
    	return new ViewModel(array('person' => $person, 'form' => $form, 'message' => $message));
    }
    
    public function deletePersonAction()
    {
    	
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
        try {
        	$deluser = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('id' => $id));
        	foreach($user->getMyFriends() as $friend){
				if($friend->getId() == $deluser->getId()){
					//$message = $friend->getDisplayName(). ' is already in your list!';
					
					$user->removeMyFriend($deluser);
					//$article = $this->getEntityManager()->find('CsnCms\Entity\Article', $id);
				    $this->getEntityManager()->persist($user);
				    $this->getEntityManager()->flush();
				    
				    return $this->redirect()->toRoute('social');
				}
			}
            			
        }
        catch (\Exception $ex) {
            echo $ex->getMessage(); // this will never be seen if you don't comment the redirect
            //return $this->redirect()->toRoute('social'));
        }	
        
        //return $this->redirect()->toRoute('social'));
    }
	
	
	public function prepareData($artcile, $data)
    {
    	$artcile->setAuthor($this->identity());
        $artcile->setFulltext($data['event']);
        $artcile->setCreated(new \DateTime());
        $artcile->setTitle($data['title']);
        
        $slug = $this->prepareSlug($data['title']);
        $artcile->setSlug($slug);
        
        $introText = $this->prepareIntroText($data['event']);
        $artcile->setIntrotext($introText);
    }
    
    public function prepareSlug($title)
    {
		// replace non letter or digits by -
		$title = preg_replace('~[^\\pL\d]+~u', '-', $title);

		// trim
		$title = trim($title, '-');

		// transliterate
		$title = iconv('utf-8', 'us-ascii//TRANSLIT', $title);

		// lowercase
		$title = strtolower($title);

		// remove unwanted characters
		$title = preg_replace('~[^-\w]+~', '', $title);

		if (empty($title))
		{
		  return 'n-a';
		}
        return $title;
    }
    
    public function prepareIntroText($event)
    {
		//Remove the HTML tags
		$event = strip_tags($event);

		// Convert HTML entities to single characters
		$event = html_entity_decode($event, ENT_QUOTES, 'UTF-8');

		// Make the string the desired number of characters
		$event = substr($event, 0, $this->introTextLenght);

		// Add an elipsis
		$event .= "…";

        return $event;
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
