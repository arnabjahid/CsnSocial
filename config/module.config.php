<?php

namespace CsnSocial;

return array(
	'controllers' => array(
        'invokables' => array(
            'CsnSocial\Controller\Index' => 'CsnSocial\Controller\IndexController',
            'CsnSocial\Controller\Person' => 'CsnSocial\Controller\PersonController',
			'CsnSocial\Controller\Comment' => 'CsnSocial\Controller\CommentController',
			'CsnSocial\Controller\Group' => 'CsnSocial\Controller\GroupController',
        ),
    ),
    'router' => array(
        'routes' => array(
			'social' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/social',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[...]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Index',
								'action'        => 'index',
							),
						),
					),
				),
			),
			'find-person' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/find-person',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Person',
						'action'        => 'findPerson',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[...]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Person',
								'action'        => 'findPerson',
							),
						),
					),
				),
			),
			'add-person' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/add-person',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Person',
						'action'        => 'addPerson',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '[/:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Person',
								'action'        => 'addPerson',
							),
						),
					),
				),
			),
			'delete-person' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/delete-person',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Person',
						'action'        => 'deletePerson',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Person',
								'action'        => 'deletePerson',
							),
						),
					),
				),
			),
			'delete-article' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/delete-article',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Index',
						'action'        => 'deleteArticle',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Index',
								'action'        => 'deleteArticle',
							),
						),
					),
				),
			),
			'view-article' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/view-article',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Index',
						'action'        => 'viewArticle',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id][/:anchor]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Index',
								'action'        => 'viewArticle',
							),
						),
					),
				),
			),
			'edit-article' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/edit-article',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Index',
						'action'        => 'editArticle',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Index',
								'action'        => 'editArticle',
							),
						),
					),
				),
			),
			'edit-comment' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/edit-comment',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Comment',
						'action'        => 'editComment',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:article]/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Comment',
								'action'        => 'editComment',
							),
						),
					),
				),
			),
			'delete-comment' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/delete-comment',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Comment',
						'action'        => 'deleteComment',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Comment',
								'action'        => 'deleteComment',
							),
						),
					),
				),
			),
			'groups' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/groups',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Group',
						'action'        => 'index',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Group',
								'action'        => 'index',
							),
						),
					),
				),
			),
			'edit-group' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/edit-group',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Group',
						'action'        => 'editGroup',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Group',
								'action'        => 'editGroup',
							),
						),
					),
				),
			),
			'delete-group' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/delete-group',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Group',
						'action'        => 'deleteGroup',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Group',
								'action'        => 'deleteGroup',
							),
						),
					),
				),
			),
			'view-members' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/view-members',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Group',
						'action'        => 'viewMembers',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Group',
								'action'        => 'viewMembers',
							),
						),
					),
				),
			),
			'add-member' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/add-member',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Group',
						'action'        => 'addMember',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]/[:id2]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Group',
								'action'        => 'addMember',
							),
						),
					),
				),
			),
			'remove-member' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/remove-member',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Group',
						'action'        => 'removeMember',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]/[:id2]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Group',
								'action'        => 'removeMember',
							),
						),
					),
				),
			),
			'vote' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/vote',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Index',
						'action'        => 'vote',
					),
				),
			'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:id]/[:id2]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnSocial\Controller',
								'controller'    => 'Index',
								'action'        => 'vote',
							),
						),
					),
				),
			),
		),
	),
	'view_manager' => array(
		'template_map' => array(
			//'csn-user/layout/nav-menu' => __DIR__ . '/../../../vendor/coolcsn/csnuser/view/csn-user/layout/nav-menu.phtml',
		),
		'template_path_stack' => array(
			'csn-social' => __DIR__ . '/../view'
		),
		'display_exceptions' => true,
    ),
        'doctrine' => array(

        // 1) for Authentication
        'authentication' => array( // this part is for the Auth adapter from DoctrineModule/Authentication
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                // object_repository can be used instead of the object_manager key
                'identity_class' => 'CsnUser\Entity\User', //'Application\Entity\User',
                'identity_property' => 'username', // 'username', // 'email',
                'credential_property' => 'password', // 'password',
                'credential_callable' => function(Entity\User $user, $passwordGiven) {
                    if ($user->getPassword() == md5('aFGQ475SDsdfsaf2342' . $passwordGiven . $user->getPasswordSalt()) &&
                        $user->getState() == 1) {
                        return true;
                    } else {
                        return false;
                    }
                },
            ),
        ),

        // 2) standard configuration for the ORM from https://github.com/doctrine/DoctrineORMModule
        // http://www.jasongrimes.org/2012/01/using-doctrine-2-in-zend-framework-2/
        // ONLY THIS IS REQUIRED IF YOU USE Doctrine in the module
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
//            'my_annotation_driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    // __DIR__ . '/../module/CsnUser/src/CsnUser/Entity' // 'path/to/my/entities',
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    // 'My\Namespace' => 'my_annotation_driver'
                    // 'CsnUser' => 'my_annotation_driver'
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),
    /*
    'translator' => array(
        'locale' => 'bg_BG'
    )*/
);
