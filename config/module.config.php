<?php

namespace CsnSocial;

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
    'doctrine' => array(
        'driver' => array(
			__NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
					__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),
);
