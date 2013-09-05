<?php
namespace CsnSocial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use CsnSocial\Form\AddEventForm;
use CsnSocial\Form\AddEventFilter;

use CsnSocial\Form\EditEventForm;
use CsnSocial\Form\EditEventFilter;

use CsnSocial\Form\AddCommentForm;
use CsnSocial\Form\AddCommentFilter;

use CsnSocial\Form\SearchFrom;
use CsnSocial\Form\AddPersonForm;

use CsnCms\Entity\Article;
use CsnCms\Entity\Comment;
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
	protected $introTextLenght = 250;
    
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
                        
						$this->prepareData($article, $data, false);
		                $this->getEntityManager()->persist($article);
		                $this->getEntityManager()->flush();
		                return $this->redirect()->toRoute('social');
                   }
        }
        
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
    
    
    public function viewArticleAction()
    {
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();

        try {
            if($article = $this->getEntityManager()->getRepository('CsnCms\Entity\Article')->findOneBy(array('id' => $id))){
            	$form = new AddCommentForm();
				$request = $this->getRequest();
				if ($request->isPost()) {
							
				            $form->setInputFilter(new AddCommentFilter($this->getServiceLocator()));
							$form->setData($request->getPost());
				            if ($form->isValid()) {
				                $data = $form->getData();
				                
				                $comment = new Comment();
				                $comment->setTitle($data['title']);
								$comment->setText($data['text']);
								$comment->setAuthor($user);
								$comment->setArticle($article);
    							$comment->setCreated(new \DateTime());
    							
								$this->getEntityManager()->persist($comment);
								$this->getEntityManager()->flush();
								return $this->redirect()->toRoute('view-article/default', array('controller' => 'index', 'action'=>'view-article', 'id' => $id));
						   }
				}
			//}
            	
            	//$comments = new Comment();
            	$comments = $this->getEntityManager()->getRepository('CsnCms\Entity\Comment')->findBy(array('article' => $id), array('created' => 'DESC'));
            	
            	return new ViewModel(array('article' => $article,'comments' => $comments, 'form' => $form, 'message' => $message));
            }else{
            	return $this->redirect()->toRoute('social');
            }
        }
        catch (\Exception $ex) {
            $message = $ex->getMessage(); // this will never be seen if you don't comment the redirect
            //return $this->redirect()->toRoute('social'));
        }
    	return new ViewModel(array('article' => $article, 'message' => $message));
    }
    
    public function editArticleAction()
    {
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
       
        try {
             if($article = $this->getEntityManager()->getRepository('CsnCms\Entity\Article')->findOneBy(array('id' => $id, 'author' => $user->getId()))){
             //TODO: Edit action and Index action may use same form and filter
				$form = new EditEventForm();
				$data = array(
					'title'    => $article->getTitle(),
					'event'   => $article->getFullText(),
				);
				$form->setData($data);
				$request = $this->getRequest();
				if ($request->isPost()) {
				            $form->setInputFilter(new EditEventFilter($this->getServiceLocator()));
							$form->setData($request->getPost());
				            if ($form->isValid()) {
				                $data = $form->getData();
				                
				                
								$this->prepareData($article, $data, true);
						        $this->getEntityManager()->persist($article);
						        $this->getEntityManager()->flush();
						        //return $this->redirect()->toRoute('edit-article/default', array('controller' => 'index', 'action'=>'edit-article', 'id' => $id));
						        $message = 'Update successful!';
						        return new ViewModel(array('form' => $form, 'message' => $message));
				           }
				}
             
            	return new ViewModel(array('article' => $article, 'form' => $form));
            }else{
            	return $this->redirect()->toRoute('social');
            }
        }
        catch (\Exception $ex) {
            $message = $ex->getMessage(); // this will never be seen if you don't comment the redirect
            //return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
            return new ViewModel(array('message' => $message));
        }

    }
    
    public function deleteArticleAction()
    {
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
        try {
        
            if($article = $this->getEntityManager()->getRepository('CsnCms\Entity\Article')->findOneBy(array('id' => $id, 'author' => $user->getId()))){
            	//Delete all comments first
				if($comments = $this->getEntityManager()->getRepository('CsnCms\Entity\Comment')->findBy(array('article' => $id, ))){
				  foreach($comments as $comment){
				  	$this->getEntityManager()->remove($comment);
		        	$this->getEntityManager()->flush();
				  }
				}
				//Delete the article
		        $this->getEntityManager()->remove($article);
		        $this->getEntityManager()->flush();	
		        return $this->redirect()->toRoute('social');
            }else{
            	return $this->redirect()->toRoute('social');
            }
        }
        catch (\Exception $ex) {
            $message = $ex->getMessage(); // this will never be seen if you don't comment the redirect
            return $this->redirect()->toRoute('social');
        }	
        
        
        
        return new ViewModel(array('person' => $person, 'form' => $form, 'message' => $message));
    }
    
    public function deleteCommentAction()
    {
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
        try {
        
            if($comment = $this->getEntityManager()->getRepository('CsnCms\Entity\Comment')->findOneBy(array('id' => $id, 'author' => $user->getId()))){
				
		        $this->getEntityManager()->remove($comment);
		        $this->getEntityManager()->flush();	
		        return $this->redirect()->toRoute('view-article/default', array('controller' => 'index', 'action'=>'view-article', 'id' => $id));
            }else{
            	return $this->redirect()->toRoute('social');
            }
        }
        catch (\Exception $ex) {
            $message = $ex->getMessage(); // this will never be seen if you don't comment the redirect
            return $this->redirect()->toRoute('social');
        }	
        
        
        
        return new ViewModel(array('person' => $person, 'form' => $form, 'message' => $message));
    }
    
    public function editCommentAction()
    {
    	
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        $article = $this->params()->fromRoute('article');
        if (!$article) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
       
        try {
             if($comment = $this->getEntityManager()->getRepository('CsnCms\Entity\Comment')->findOneBy(array('id' => $id, 'author' => $user->getId()))){
             //TODO: Edit action and Index action may use same form and filter
				$form = new AddCommentForm();
				$form->get('submit')->setValue('Update comment');
				$data = array(
					'title'    => $comment->getTitle(),
					'text'   => $comment->getText(),
				);
				$form->setData($data);
				$request = $this->getRequest();
				if ($request->isPost()) {
				            $form->setInputFilter(new AddCommentFilter($this->getServiceLocator()));
							$form->setData($request->getPost());
				            if ($form->isValid()) {
				                $data = $form->getData();
				                
				                $comment->setTitle($data['title']);
				                $comment->setText($data['text']);
								//$this->prepareData($article, $data, true); // ???????
						        $this->getEntityManager()->persist($comment);
						        $this->getEntityManager()->flush();
						        //return $this->redirect()->toRoute('edit-article/default', array('controller' => 'index', 'action'=>'edit-article', 'id' => $id));
						        $message = 'Update successful!';
						        return new ViewModel(array('form' => $form, 'message' => $message, 'id'=>$article));
				           }
				}
             
            	return new ViewModel(array('form' => $form, 'id'=>$article));
            }else{
            	return $this->redirect()->toRoute('social');
            }
        }
        catch (\Exception $ex) {
            $message = $ex->getMessage(); // this will never be seen if you don't comment the redirect
            //return $this->redirect()->toRoute('csn-cms/default', array('controller' => 'article', 'action' => 'index'));
            return new ViewModel(array('message' => $message, 'id'=>$article));
        }
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
	
	
	public function prepareData($artcile, $data, $update)
    {
    	if(!$update){
    		$artcile->setAuthor($this->identity());
    		$artcile->setCreated(new \DateTime());
    	}
        $artcile->setFulltext($data['event']);
        
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
		//$event = strip_tags($event);
		$original = $event;
		// Convert HTML entities to single characters
		$event = html_entity_decode($event, ENT_QUOTES, 'UTF-8');
		
		// Get first N characters
		/*$firsNChars = substr($event, 0, $this->introTextLenght);

		//If exist close tag
		if($videoCloseTagPos = strpos($firsNChars, "</iframe>")){
			$videoOpenTagPos = strpos($firsNChars, "<iframe");
			$videoWithOutText = $videoCloseTagPos - $videoOpenTagPos;

			// Make the string the desired number of characters
			$event = substr($event, 0, ($this->introTextLenght+$videoWithOutText));
			
		// If not exist close tag
		}elseif($videoOpenTagPos = strpos($firsNChars, "<iframe")){
			$event = substr($original, 0, $videoOpenTagPos);
		}else{
			$event = substr($original, 0, ($this->introTextLenght));
		}*/
		
		$event = substr($original, 0, $this->introTextLenght);
		if(strlen($original) > $this->introTextLenght){
			// Add an elipsis
			$event .= "…";
		}

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
