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

 * @version 20140506 
 * @link https://github.com/KatsuoRyuu/
 */

use Doctrine\ORM\Mapping as ORM,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface,
    Zend\Form\Annotation,
    Doctrine\Common\Collections\Collection,
    Doctrine\Common\Collections\ArrayCollection;


/**
 * @Annotation\Name("company")
 * @ Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @ORM\Entity
 * @ORM\Table(name="contact_company")
 */
class Company {
    
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
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Name:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "Your first name"})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $name;
        
    /**
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Required({"required":"false" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"About:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "About the company"})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $about;
         
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Address:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "Address"})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $address;
        
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Country:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "Country name"})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $country;
        
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Zip:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "postal code"})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $zip;
        
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"City:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "City name ..."})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $city;
        
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Phone:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "Phone number ..."})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $phone;
    
    /**
     * @Annotation\Exclude()
     * 
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Contact\Entity\Contact")
     * @ORM\JoinTable(name="contact_company_contact_linker",
     *      joinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")}
     * )
     */
    protected $contact;   
        
    public function __construct() {
        $this->contact = new ArrayCollection();
    }
    
    
    
    /**
     * 
     */
    public function getContacts(){
        return $this->contact;
    }
    
    /**
     * 
     */
    public function addContact(Contact $contact){
        $this->contact->add($contact);
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
        foreach ($array as $key => $var){
            $this->$key = $var;
        }
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
