<?php

namespace Contact\Controller;

/**
 * @encoding UTF-8
 * @note *
 * @todo *
 * @package PackageName
 * @author Anders Blenstrup-Pedersen - KatsuoRyuu <anders-github@drake-development.org>
 * @license *
 * The Ryuu Technology License
 *
 * Copyright 2014 Ryuu Technology by 
 * KatsuoRyuu <anders-github@drake-development.org>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * Ryuu Technology shall be visible and readable to anyone using the software 
 * and shall be written in one of the following ways: 竜技術, Ryuu Technology 
 * or by using the company logo.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *

 * @version 20140514 
 * @link https://github.com/KatsuoRyuu/
 */
use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Contact\Entity\Contact;
use Contact\Entity\Company;
use Contact\Entity\Message;
use Contact\Controller\EntityUsingController;
use Zend\Mail;
use Zend\Mime;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class MessageController extends EntityUsingController {


    public function addAction(){

        $message =  new Message();
        $isFile = false;
        
        
        $builder = new AnnotationBuilder();
        $form   =  $builder->createForm($message);
        $form->bind($message);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->bind($message);
            
            if ($request->getFiles()['file']['tmp_name'] != "" && $this->getConfiguration('fileupload')){
                $form->add(array( 
                    'name' => 'upload', 
                    'type' => 'file', 
                    'attributes' => array( 
                        'required' => 'required', 
                    ), 
                    'options' => array( 
                        'label' => 'File Upload', 
                    ), 
                ));
                $requestData = array_merge_recursive((array) $request->getPost(),(array) $request->getFiles());
                $isFile  = true;
            } else {
                $requestData = (array) $request->getPost();
            }

            $form->setData($requestData);

            if ($form->isValid()) {
                $em = $this->getEntityManager();
                
                $contact = $this->getEntityManager()->getRepository('Contact\Entity\Contact')->findOneBy(array('id'=>$request->getPost()->about));
                
                $message->__add($contact,'about');
                
                if ($isFile) {
                    $message->__add($this->storeFile($request->getFiles()), 'file');
                }
                
                $em->persist($message);
                $em->flush();  
                
                
                $this->sendMail($message);

                $this->flashMessenger()->addMessage('Contact Saved');

                return $this->redirect()->toRoute('contact');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    private function storeFile($file){

        if (!$this->getConfiguration('fileupload')){
            return null;
        }

        $fileRepo = $this->getServiceLocator()->get('FileRepository');
        $file = $fileRepo->save($file['file']['tmp_name'],$file['file']['name']);
        return $file;
    }
    
    private function sendMail($message){
        
        $mail = new Mail\Message();
        
        $parts = array();
        
        $bodyMessage = new Mime\Part();
        $bodyMessage->type = 'text/plain';
        
        $parts[] = $bodyMessage;
        
        if ($message->__get("file")->count() > 0){
            foreach ($message->__get("file") as $file) {
                $fileRepo = $this->getServiceLocator()->get('FileRepository');
                $fileContent = fopen($fileRepo->getRoot().'/'.$file->getSavePath(), 'r');
                
                $attachment = new Mime\Part($fileContent);
                $attachment->type = $file->getMimetype();
                $attachment->filename = $file->getName();
                $attachment->encoding = Mime\Mime::ENCODING_BASE64;
                $attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;
                $parts[] = $attachment;
            }

        }

        $bodyPart = new Mime\Message();

        // add the message body and attachment(s) to the MimeMessage
        $bodyPart->setParts($parts);
        
        $mail
            ->addFrom($message->__get('email'), $message->__get('name'))
            ->addTo($message->__get('about')->first()->__get('email'))
            ->setSubject($message->__get('subject'))
            ->setBody($bodyPart)
            ->addReplyTo($message->__get('email'), $message->__get('name'))
            ->setSender($message->__get('email'), $message->__get('name'))
            ->setEncoding("UTF-8")
            ->setBody($bodyPart);
        // Setup SMTP transport using LOGIN authentication
        
        $this->getMailTransport()->send($mail);
        
    }


}
