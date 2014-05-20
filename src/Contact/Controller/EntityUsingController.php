<?php
namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class EntityUsingController extends AbstractActionController
{

	/**
	* @var EntityManager
	*/
	protected $entityManager;

	/**
	* @var BaseNamespace
	*/
	protected $baseNamespace;

	/**
	* @var configuration
	*/
	protected $configuration;
	
	/**
	* Sets the EntityManager
	*
	* @param EntityManager $em
	* @access protected
	* @return PostController
	*/
	protected function setEntityManager(\Doctrine\ORM\EntityManager $em)
	{
		$this->entityManager = $em;
		return $this;
	}
	
	/**
	* Returns the EntityManager
	*
	* Fetches the EntityManager from ServiceLocator if it has not been initiated
	* and then returns it
	*
	* @access protected
	* @return EntityManager
	*/
	protected function getEntityManager()
	{
		if (null === $this->entityManager) {
			$this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
		}
		return $this->entityManager;
	}	
    
	/**
	* Sets the base namespace
	*
	* @param string $space
	* @access protected
	* @return PostController
	*/
	protected function setBaseNamespace($space)	{
        
        $space = explode('\\',$space);
		$this->baseNamespace = $space[0];
		return $this;
	}
	
	/**
	 * Returns the base namespace
	 *
	 * Fetches the string of the base Namespace ex. contact\controller 
     * will return contact only
	 *
	 * @access protected
     * @return String
	 */
	protected function getBaseNamespace() {
        
		if (null === $this->baseNamespace) {
			$this->setBaseNamespace(__NAMESPACE__);
		}
		return $this->baseNamespace;
	}
    
	/**
	* Sets the configuration for later easier access
	*
	* @access protected
	* @return PostController
	*/
	protected function setConfiguration() {
        $this->configuration = $this->getServiceLocator()->get('config')[$this->getBaseNamespace()]['config'];
		return $this;
	}
	
	/**
	 * Returns the configuration
	 *
	 * Fetches the string of the base configuration name ex
     * array(
     *      test => someconfig,
     *      foo  => array(
     *           foobar => barfoo,
     *           ),
     *      );
     * 
     * getConfiguration(test) returns string(someconfig)
     * getConfiguration(foo)  returns array(foobar => barfoo)
	 *
     * @param String $searchString the name of the base configuration
	 * @access protected
     * @return String or array.
	 */
	protected function getConfiguration($searchString)	{
        
		if (null === $this->configuration) {
			$this->setConfiguration();
		}
		return $this->configuration[$searchString];
	}
    
} 