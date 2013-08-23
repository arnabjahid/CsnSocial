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
		),
	),
	'view_manager' => array(
		'template_path_stack' => array(
			'csn-social' => __DIR__ . '/../view'
		),
		'display_exceptions' => true,
    ),
);
