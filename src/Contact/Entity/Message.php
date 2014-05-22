<?php

namespace Contact\Entity;

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

 * @version 20140508 
 * @link https://github.com/KatsuoRyuu/
 */

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;
use Zend\Form\Annotation;


/**
 * @Annotation\Name("message")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @ORM\Entity
 * @ORM\Table(name="contact_message")
 */
class Message {

    /**
     * @Annotation\Exclude()
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer 
     */
    private $id;

    /**
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Flags({"priority": 600})
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StringTrim"})
     * @ Annotation\Validator({"name":"StringLength"})
     * @Annotation\Options({"label":"About:"})
     * @Annotation\Attributes({"options":{"1":"PlaceHolder","2":"Test"}})
     * 
     * @ORM\ManyToMany(targetEntity="Contact\Entity\Contact")
     * @ORM\JoinTable(name="contact_message_contact_linker",
     *      joinColumns={@ORM\JoinColumn(name="message_id", referencedColumnName="id", nullable=false)},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id", nullable=false)}
     *      )
     */
    private $about;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Flags({"priority": 500})
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Name:"})
     * @Annotation\Attributes({"required": true,"placeholder": "Your name ... "})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $name;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Flags({"priority": 500})
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"email:"})
     * @Annotation\Attributes({"required": true,"placeholder": "Your email..."})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $email;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Flags({"priority": 400})
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Subject:"})
     * @Annotation\Attributes({"required": true,"placeholder": "Subject ... "})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $subject;

    /**
     * @Annotation\Exclude()
     * @ Annotation\Type("Zend\Form\Element\File")
     * @ Annotation\Flags({"priority": 300})
     * @ Annotation\Required({"required":false })
     * @ Annotation\Filter({"name":"StringTrim","filerenameupload":{"target": "./img","randomize":true}})
     * @ Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"StringLength"})
     * @ Annotation\Options({"label":"File:"})
     * @ Annotation\Attributes({"required": false})
     * 
     *
     * @ORM\ManyToMany(targetEntity="FileRepository\Entity\File")
     * @ORM\JoinTable(name="contact_message_file_linker",
     *      joinColumns={@ORM\JoinColumn(name="message_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     *      )
     * @var String
     */
    private $file;

    /**
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Flags({"priority": 200})
     * @ Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Message:"})
     * @Annotation\Attributes({"required": true,"placeholder": "Message ... "})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $message;
    
    /**
     * 
     * 
     */
    public function __construct() {
        $this->about = new ArrayCollection();
        $this->file = new ArrayCollection();
    }
    
    public function __add($value,$key){
        if(!$this->$key instanceof ArrayCollection) {
            $this->$key = new ArrayCollection();
        }
        $this->$key->add($value);
    }

    /**
     * WARNING USING THESE IS NOT SAFE. there is no checking on the data and you need to know what
     * you are doing when using these.
     * But it a great function for lazy people ;)
     * 
     * @param ANY $value
     * @param ANY $key
     * @return $value
     */
    public function __set($value,$key){
        return $this->$key = $value;
    }    

    /**
     * WARNING USING THESE IS NOT SAFE. there is no checking on the data and you need to know what
     * you are doing when using these.
     * But it a great function for lazy people ;)
     * 
     * @param ANY $value
     * @param ANY $key
     * @return $value
     */
    public function __get($key){
        return $this->$key;
    }    

    /**
     * WARNING USING THESE IS NOT SAFE. there is no checking on the data and you need to know what
     * you are doing when using these.
     * This is used to exchange data from form and more when need to store data in the database.
     * and again ist made lazy, by using foreach without data checks
     * 
     * @param ANY $value
     * @param ANY $key
     * @return $value
     */
    public function populate($array){
        
        $this->__add($array['about'],'about');
        $this->__add($array['file'],'file' );
        
        $this->email    = $array['email'];
        $this->message  = $array['message'];
        $this->name     = $array['name'];
        $this->subject  = $array['subject'];
    }


    /**
    * Get an array copy of object
    *
    * @return array
    */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


}
