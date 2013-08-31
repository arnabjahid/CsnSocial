<?php

return array(
	'controllers' => array(
        'invokables' => array(
            'CsnSocial\Controller\Index' => 'CsnSocial\Controller\IndexController',
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
			'find-people' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/find-people',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnSocial\Controller',
						'controller'    => 'Index',
						'action'        => 'findPeople',
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
								'action'        => 'findPeople',
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
						'controller'    => 'Index',
						'action'        => 'addPerson',
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
						'controller'    => 'Index',
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
								'controller'    => 'Index',
								'action'        => 'deletePerson',
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
);
