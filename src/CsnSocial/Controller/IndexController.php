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

use CsnSocial\Form\AddEventForm;
use CsnSocial\Form\AddEventFilter;

use CsnSocial\Form\EditEventForm;
use CsnSocial\Form\EditEventFilter;

use CsnSocial\Form\AddCommentForm;
use CsnSocial\Form\AddCommentFilter;

use CsnCms\Entity\Article;
use CsnCms\Entity\Vote;
use CsnCms\Entity\Comment;
use CsnUser\Entity\User;

/**
 * Index Controller
 */
class IndexController extends AbstractActionController {

    /**             
	 * @var Doctrine\ORM\EntityManager
	 */                
	protected $em;
	
	/**             
	 * @var Zend translator
	 */  
	protected $translator;
	
	/**             
	 * @var Intro text lenght
	 */ 
	protected $introTextLenght = 250;
    
    /**
     * Index action
     *
     * Main action for the module. Used to prepare articles, friends, followers and send to the view.
     *
     * @return viewModel
     */
    public function indexAction() {

		if (!$user = $this->identity()) {
			return $this->redirect()->toRoute('login', array('controller' => 'index', 'action' => 'login'));
		}
		
		$user = $this->identity();
		
		$article = new Article();
		$form = new AddEventForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
                    $form->setInputFilter(new AddEventFilter($this->getServiceLocator()));
					$form->setData($request->getPost());
                    if ($form->isValid()) {
                        $data = $form->getData();
                        
						$this->prepareData($article, $data, false);
						//echo '<pre>';
						//print_r($article);
						//echo '</pre>';
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
    
    /**
     * View article action
     *
     * Prepare article and its comments by id
     *
     * @return viewModel
     */
    public function viewArticleAction() {
    
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();

		if($article = $this->getEntityManager()->getRepository('CsnCms\Entity\Article')->findOneBy(array('id' => $id))) {
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
			$comments = $this->getEntityManager()->getRepository('CsnCms\Entity\Comment')->findBy(array('article' => $id), array('created' => 'DESC'));
			return new ViewModel(array('article' => $article,'comments' => $comments, 'form' => $form, 'message' => $message));
		}else {
			return $this->redirect()->toRoute('social');
		}
	
    	return new ViewModel(array('article' => $article, 'message' => $message));
    }
    
    /**
     * Edit article action
     *
     * Edit article by id 
     * @return viewModel
     */
    public function editArticleAction() {
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();

		if($article = $this->getEntityManager()->getRepository('CsnCms\Entity\Article')->findOneBy(array('id' => $id, 'author' => $user->getId()))) {
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

					$message = $this->getTranslator()->translate('Update successful!');
					return new ViewModel(array('form' => $form, 'message' => $message));
				}
			}
             
			return new ViewModel(array('article' => $article, 'form' => $form));
		}else {
			return $this->redirect()->toRoute('social');
		}

		return new ViewModel(array('message' => $message));
    }
    
    /**
     * Delete article action
     *
     * Delete article by id
     *
     * @return viewModel
     */
    public function deleteArticleAction() {
    
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
		if($article = $this->getEntityManager()->getRepository('CsnCms\Entity\Article')->findOneBy(array('id' => $id, 'author' => $user->getId()))) {
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
		}else {
			return $this->redirect()->toRoute('social');
		}
        
        return new ViewModel(array('person' => $person, 'form' => $form, 'message' => $message));
    }
    

	/**
     * Prepare data method
     *
     * Prepare data to be inserted in database
     */
	public function prepareData($article, $data, $update) {
		
		// prepare data for updating
    	if(!$update){
    		$article->setAuthor($this->identity());
    		$article->setCreated(new \DateTime());
    	}
    	
    	$vote = new Vote();

		$article->setVote($vote);
    	// prepare data for inserting
        $article->setFulltext($data['event']);
        
        $article->setTitle($data['title']);
        
        $slug = $this->prepareSlug($data['title']);
        $article->setSlug($slug);
        
        $introText = $this->prepareIntroText($data['event']);
        $article->setIntrotext($introText);
    }
    
    /**
     * Prepare slug method
     *
     * Prepare slug which replace article id in browser url with title of the article. For example (www.host.com/articles/this-is-slug)
     *
     * @return slug 
     */
    public function prepareSlug($title) {
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
    
    /**
     * Prepare intro text method
     *
     * Get the full article text and create short intro text whitch lenght is set in "introTextLenght" (by default 250 symbols)
     *
     * @return Intro text 
     */
    public function prepareIntroText($event) {
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
		if(strlen($original) > $this->introTextLenght) {
			// Add an elipsis
			$event .= "…";
		}

        return $event;
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
