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

use CsnSocial\Form\AddCommentForm; // using for edit comment
use CsnSocial\Form\AddCommentFilter;

/**
 * Comment Controller
 */
class CommentController extends AbstractActionController {

    /**             
	 * @var Doctrine\ORM\EntityManager
	 */                
	protected $em;
	
	/**             
	 * @var Zend translator
	 */  
	protected $translator;

	/**
     * Edit comment action
     *
     * Edit comment from article view
     *
     */
    public function editCommentAction() {
    	
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        $article = $this->params()->fromRoute('article');
        if (!$article) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
		if($comment = $this->getEntityManager()->getRepository('CsnCms\Entity\Comment')->findOneBy(array('id' => $id, 'author' => $user->getId()))) {
			//Edit action and Index action use same form and filter
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
	
					$this->getEntityManager()->persist($comment);
					$this->getEntityManager()->flush();

					$message = $this->getTranslator()->translate('Update successful!');
					return new ViewModel(array('form' => $form, 'message' => $message, 'id'=>$article));
				}
			}
             
			return new ViewModel(array('form' => $form, 'id'=>$article));
		}else {
			return $this->redirect()->toRoute('social');
		}
		
		return new ViewModel(array('message' => $message, 'id'=>$article));
    }
    
    /**
     * Delete comment action
     *
     * Delete comment from article view
     *
     */
    public function deleteCommentAction() {
    	$id = $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('social');
        }
        
        $user = $this->identity();
        
		if($comment = $this->getEntityManager()->getRepository('CsnCms\Entity\Comment')->findOneBy(array('id' => $id, 'author' => $user->getId()))) {
			$redirectId = $comment-> getArticle()->getId();
			
			$this->getEntityManager()->remove($comment);
			$this->getEntityManager()->flush();	
			return $this->redirect()->toRoute('view-article/default', array('controller' => 'index', 'action'=>'view-article', 'id' => $redirectId));
		}else {
			return $this->redirect()->toRoute('social');
		}

        return new ViewModel(array('person' => $person, 'form' => $form, 'message' => $message));
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