<?php
namespace Contact;

return array(
    'controllers' => array(
        'invokables' => array(
            'Contact\Controller\Contact' => 'Contact\Controller\ContactController',
            'Contact\Controller\Index' => 'Contact\Controller\IndexController',
            'Contact\Controller\Company' => 'Contact\Controller\CompanyController',
            'Contact\Controller\Staff' => 'Contact\Controller\StaffController',
            'Contact\Controller\Message' => 'Contact\Controller\MessageController',
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'contact' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/contact',
                    'defaults' => array(
                        'controller' => 'Contact\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'contactview' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/view[/][:action][/:id][/:id2]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Contact\Controller\Index',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'company' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/company[/][:action][/:id][/:id2]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Contact\Controller\Company',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'contact' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/contact[/][:action][/:id][/:id2]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Contact\Controller\Contact',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'staff' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/staff[/][:action][/:id][/:id2]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Contact\Controller\Staff',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'message' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/message[/][:action]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Contact\Controller\Message',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
	
    'view_manager' => array(
        'template_path_stack' => array(
            'contact' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);