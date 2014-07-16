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

class IndexController extends EntityUsingController {
	
	protected $ContactTable;
	
    public function indexAction()
    {
        $em = $this->getEntityManager();

        $company = $em->getRepository('Contact\Entity\Company')->findOneBy(array(), array('name' => 'ASC'));
        $staffs = $em->getRepository('Contact\Entity\Staff')->findBy(array(), array('name' => 'ASC'));

        if (!$company){
            $company = new Company();
        }
        if (!$staffs){
            $staffs = new Staff();
        }
        
        
        $builder    = new AnnotationBuilder();
        $message    = new Message();
        $companyForm= $builder->createForm($message);
        
        
        if ($this->getConfiguration('fileupload')){
            $companyForm->add(array( 
                'name' => 'file', 
                'priority' => 300,
                'type' => 'file',
                'options' => array( 
                    'label' => 'File:', 
                ), 
            ),array('priority' => 300));
        }
        

        $contactsArray = $company->getContacts()->toArray();
        $mails = array();
        foreach($contactsArray as $contact){
            $mails[$contact->__get('id')] = $contact->__get('area');
        }
        $companyForm->get('about')->setValueOptions($mails);

        return new ViewModel(array('company'=>$company,'staffs'=>$staffs,'companyForm'=>$companyForm));
    }
    
    public function staffAction()
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
    
    public function companyAction()
    {
        return $this->indexAction();
    }
    
    public function messageSendAction(){
        
    }

}