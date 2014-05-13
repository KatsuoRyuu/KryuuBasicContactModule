<?php
namespace Contact\Controller;

use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Contact\Entity\Contact;
use Contact\Entity\Company;
use Contact\Entity\Message;
use Contact\Entity\CompanyContact;
use Contact\Form;
use Contact\Controller\EntityUsingController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class CompanyController extends EntityUsingController
{
	
	protected $ContactTable;
	
    public function indexAction()
    {
        $em = $this->getEntityManager();

        $companies = $em->getRepository('Contact\Entity\Company')->findBy(array(), array('name' => 'ASC'));

        return new ViewModel(array('companies'=>$companies));
    }

    public function addAction()
    {
        return $this->editAction();
    }

    public function editAction(){
     
        
        $company =  new Company();

        if ($this->params('id') > 0) {
            $company = $this->getEntityManager()->getRepository('Contact\Entity\Company')->find($this->params('id'));
        }

        $builder = new AnnotationBuilder();
        $form   =  $builder->createForm($company);
        $form->bind($company);

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->bind($company);
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $em = $this->getEntityManager();
               
                $em->persist($company);
                $em->flush();                

                $this->flashMessenger()->addMessage('Contact Saved');

                return $this->redirect()->toRoute('contact/company');
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
        $company = $this->getEntityManager()->getRepository('Contact\Entity\Company')->find($this->params('id'));

        if ($company) {
            $em = $this->getEntityManager();
            $title = $company->__get('name');
            $id = $company->__get('id');
            $em->remove($company);
            $em->flush();

            $this->flashMessenger()->addMessage('Company Deleted ('.$id.':'.$title.')');
        }

        return $this->redirect()->toRoute('contact/company');
    }
    
    public function addContactAction(){   
        $company =  new Company();
        $em = $this->getEntityManager();

        if ($this->params('id') > 0) {
            $company = $this->getEntityManager()->getRepository('Contact\Entity\Company')->find($this->params('id'));
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
                var_dump($request->getPost());
                $contact = $this->getEntityManager()->getRepository('Contact\Entity\Contact')->find($request->getPost('contact'));
                $company->addContact($contact);
               
                $em->persist($company);
                $em->flush();                

                $this->flashMessenger()->addMessage('Contact Saved');

                return $this->redirect()->toRoute('contact/company');
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'contacts'=> $contacts,
            'id'=>$this->params('id'),
        ));
    }

    public function deleteContactAction(){
        $company = $this->getEntityManager()->getRepository('Contact\Entity\Company')->find($this->params('id'));
        $contact = $this->getEntityManager()->getRepository('Contact\Entity\Contact')->find($this->params('id2'));
        
        if ($company && $contact) {
            $em = $this->getEntityManager();
            $updatedContacts = $company->getContacts();
            $updatedContacts->removeElement($contact);
            $em->flush();

            $this->flashMessenger()->addMessage('Company Contact Deleted ('.$company->__get('name').':'.$contact->__get('email').')');
        }

        return $this->redirect()->toRoute('contact/company');
    }

}