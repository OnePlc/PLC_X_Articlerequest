<?php
/**
 * module.config.php - Articlerequest Config
 *
 * Main Config File for Articlerequest Module
 *
 * @category Config
 * @package Articlerequest
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Articlerequest;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    # Articlerequest Module - Routes
    'router' => [
        'routes' => [
            # Module Basic Route
            'articlerequest' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/articlerequest[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9_-]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ArticlerequestController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'articlerequest-api' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/articlerequest/api[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    # View Settings
    'view_manager' => [
        'template_path_stack' => [
            'articlerequest' => __DIR__ . '/../view',
        ],
    ],

    # Translator
    'translator' => [
        'locale' => 'de_DE',
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
];
