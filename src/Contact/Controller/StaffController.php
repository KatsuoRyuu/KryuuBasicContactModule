<?php
namespace Contact\Controller;

use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Contact\Entity\Contact;
use Contact\Entity\Message;
use Contact\Entity\Staff;
use Contact\Form;
use Contact\Controller\EntityUsingController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class StaffController extends EntityUsingController
{	
	protected $ContactTable;
	
    public function indexAction()
    {
        $em = $this->getEntityManager();

        $companies = $em->getRepository('Contact\Entity\Staff')->findBy(array(), array('name' => 'ASC'));

        return new ViewModel(array('companies'=>$companies));
    }

    public function addAction()
    {
        return $this->editAction();
    }

    public function editAction(){
     
        
        $staff =  new Staff();

        if ($this->params('id') > 0) {
            $staff = $this->getEntityManager()->getRepository('Contact\Entity\Staff')->find($this->params('id'));
        }

        $builder = new AnnotationBuilder();
        $form   =  $builder->createForm($staff);
        $form->bind($staff);

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->bind($staff);
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $em = $this->getEntityManager();
               
                $em->persist($staff);
                $em->flush();                

                $this->flashMessenger()->addMessage('Contact Saved');

                return $this->redirect()->toRoute('contact/staff');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    /**
    * Delete action
    *
    */
    public function deleteAction()
    {
        $staff = $this->getEntityManager()->getRepository('Contact\Entity\Staff')->find($this->params('id'));

        if ($staff) {
            $em = $this->getEntityManager();
            $title = $staff->__get('name');
            $id = $staff->__get('id');
            $em->remove($staff);
            $em->flush();

            $this->flashMessenger()->addMessage('Staff Deleted ('.$id.':'.$title.')');
        }

        return $this->redirect()->toRoute('contact/staff');
    }
    
    public function addContactAction(){   
        $staff =  new Staff();
        $em = $this->getEntityManager();

        if ($this->params('id') > 0) {
            $staff = $this->getEntityManager()->getRepository('Contact\Entity\Staff')->find($this->params('id'));
        }

        $contacts = $em->getRepository('Contact\Entity\Contact')->findBy(array(), array('area' => 'ASC'));
        $dropdown = array();
        foreach ($contacts as $contact){
            $dropdown[$contact->__get('id')] = $contact->__get('area').' - '.$contact->__get('email');
        }
        $form =  new Form\SelectContact(null);
        $form->get('contact')->setValueOptions($dropdown);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $contact = $this->getEntityManager()->getRepository('Contact\Entity\Contact')->find($request->getPost('contact'));
                $staff->addContact($contact);
               
                $em->persist($staff);
                $em->flush();                

                $this->flashMessenger()->addMessage('Contact Saved');

                return $this->redirect()->toRoute('contact/staff');
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'contacts'=> $contacts,
            'id'=>$this->params('id'),
        ));
    }
    
    public function deleteContactAction(){
        $staff = $this->getEntityManager()->getRepository('Contact\Entity\Staff')->find($this->params('id'));
        $contact = $this->getEntityManager()->getRepository('Contact\Entity\Contact')->find($this->params('id2'));
        if ($staff && $contact) {
            $em = $this->getEntityManager();
            $updatedContacts = $staff->getContacts();
            $updatedContacts->removeElement($contact);
            $em->flush();

            $this->flashMessenger()->addMessage('Staff Contact Deleted ('.$staff->__get('name').':'.$contact->__get('email').')');
        }

        return $this->redirect()->toRoute('contact/staff');
    }
}