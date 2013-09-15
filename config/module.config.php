<?php

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
    'translator' => array(
        'locale' => 'bg_BG'
    )
);
