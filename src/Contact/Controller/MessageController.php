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

    class MessageController extends EntityUsingController {


        public function addAction(){

            $message =  new Message();

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
                } else {
                    $requestData = (array) $request->getPost();
                }
                
                $form->setData($requestData);
                
                if ($form->isValid()) {
                    $em = $this->getEntityManager();

                    $message->__set($this->storeFile($request->getFiles()), 'file');
                        
                    $em->persist($message);
                    $em->flush();                

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
            $file = $fileRepo->save($file['upload']['tmp_name']);
            return $file->getId();
        }
    }
