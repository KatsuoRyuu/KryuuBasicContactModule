<?php
namespace Contact\Controller;

use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Contact\Entity\Contact;
use Contact\Entity\Message;
use Contact\Entity\CompanyContact;
use Contact\Controller\EntityUsingController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class ContactController extends EntityUsingController
{
	
	protected $ContactTable;
	
	
    public function indexAction()
    {
        $em = $this->getEntityManager();

        $contacts = $em->getRepository('Contact\Entity\Contact')->findBy(array(), array('area' => 'ASC'));

        return new ViewModel(array('contacts'=>$contacts));
    }
    
    public function messageSendAction(){
        
    }

    public function addAction()
    {
        return $this->editAction();
    }

    public function editAction(){
     
        
        $contact =  new Contact();

        if ($this->params('id') > 0) {
            $contact = $this->getEntityManager()->getRepository('Contact\Entity\Contact')->find($this->params('id'));
        }

        $builder = new AnnotationBuilder();
        $form   =  $builder->createForm($contact);
        $form->bind($contact);

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->bind($contact);
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $em = $this->getEntityManager();
               
                $em->persist($contact);
                $em->flush();                

                $this->flashMessenger()->addMessage('Contact Saved');

                return $this->redirect()->toRoute('contact/contact');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }
    
    public function deleteAction()
    {
    }

}