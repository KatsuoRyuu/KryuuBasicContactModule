<?php
namespace Contact\Controller;

use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Contact\Entity\Contact;
use Contact\Entity\Message;
use Contact\Entity\Staff;
use Contact\Entity\Company;
use Contact\Controller\EntityUsingController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class IndexController extends EntityUsingController
{
	
	protected $ContactTable;
	
    public function indexAction()
    {
        $em = $this->getEntityManager();

        $company = $em->getRepository('Contact\Entity\Company')->findOneBy(array(), array('name' => 'ASC'));
        $staffs = $em->getRepository('Contact\Entity\Staff')->findBy(array(), array('name' => 'ASC'));

        $builder    = new AnnotationBuilder();
        $message    = new Message();
        $companyForm= $builder->createForm($message);
        $contactsArray = $company->getContacts()->toArray();
        $mails = array();
        foreach($contactsArray as $contact){
            $mails[$contact->__get('id')] = $contact->__get('area');
        }
        
        $companyForm->get('about')->setValueOptions($mails);
        return new ViewModel(array('company'=>$company,'staffs'=>$staffs,'companyForm'=>$companyForm));
    }
    
    public function viewStaffAction()
    {
        $staffs = new Staff();
        
        $em = $this->getEntityManager();
        
        if ($this->params('id')){
            $staffs = $em->getRepository('Contact\Entity\Staff')->findBy(array('id'=>$this->params('id')), array('name' => 'ASC'));
        } else {
            $staffs = $em->getRepository('Contact\Entity\Staff')->findBy(array(), array('name' => 'ASC'));
        }

        
        
        $company = $em->getRepository('Contact\Entity\Company')->findOneBy(array(), array('name' => 'ASC'));
        if(!$company){
            $company = new Company();
        }

        $builder    = new AnnotationBuilder();
        $message    = new Message();
        $companyForm= $builder->createForm($message);
        $contactsArray = $company->getContacts()->toArray();
        $mails = array();
        foreach($contactsArray as $contact){
            $mails[$contact->__get('id')] = $contact->__get('area');
        }
        
        $companyForm->get('about')->setValueOptions($mails);
        return new ViewModel(array('company'=>$company,'staffs'=>$staffs,'companyForm'=>$companyForm));
    }
    
    public function viewCompanyAction()
    {
        return $this->indexAction();
    }
    
    public function messageSendAction(){
        
    }

}