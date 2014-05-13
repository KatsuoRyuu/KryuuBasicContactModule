<?php

namespace Contact\Entity;

/**
 * @encoding UTF-8
 * @note *
 * @todo build in datachecks for security, and stability.
 * @package Contact
 * @author Anders Blenstrup-Pedersen - KatsuoRyuu <anders-github@drake-development.org>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 20140407 
 * @link https://github.com/KatsuoRyuu/
 */

use Doctrine\ORM\Mapping as ORM;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\Collection,
    Doctrine\Common\Collections\ArrayCollection;
use    Zend\Form\Annotation;


/**
 * @Annotation\Name("staff")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @ORM\Entity
 * @ORM\Table(name="contact_staff")
 */
class Staff{
    
    
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
     * @Annotation\Options({"label":"Name:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "About the staff member"})
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
     * @Annotation\Options({"label":"Last name:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "Your last name"})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $lastname;
     
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"phone"})
     * @Annotation\Options({"label":"Phone:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "Phone Number ..."})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $phone;
     
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @ Annotation\Validator({"name":"phone"})
     * @Annotation\Options({"label":"Country:","priority":"400"})
     * @Annotation\Attributes({"required": true,"placeholder": "Country name ..."})
     * 
     * @ORM\Column(type="string")
     * @var String
     */
    private $country;
    
    /**
     * @Annotation\Exclude()
     * 
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Contact\Entity\Contact")
     * @ORM\JoinTable(name="contact_staff_contact_linker",
     *      joinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="staff_id", referencedColumnName="id")}
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
     * 
     */
    public function setContacts(ArrayCollection $contacts){
        $this->contact = $contacts;
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
