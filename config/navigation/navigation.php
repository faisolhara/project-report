<?php

return 
[
        [
        'label' => 'Master',
        'icon' => 'fa fa-users',
        'children' =>
            [
                [
                    'label'     => 'Access Control',
                    'icon'      => 'arrow-up',
                    'route'     => 'master/access-control',
                    'resource'  => 'Master\Access Control',
                    'privilege' => 'view',
                ],
            ]
        ],
        [
        'label' => 'Transaction',
        'icon' => ' fa fa-line-chart',
        'children' =>
            [
                [
                    'label'     => 'Project',
                    'icon'      => 'arrow-up',
                    'route'     => 'transaction/project',
                    'resource'  => 'Transaction\Project',
                    'privilege' => 'view',
                ],
                [
                    'label'     => 'Progress',
                    'icon'      => 'arrow-up',
                    'route'     => 'transaction/update-progress',
                    'resource'  => 'Transaction\Update Progress',
                    'privilege' => 'view',
                ],
                [
                    'label'     => 'Validation Progress',
                    'icon'      => 'arrow-up',
                    'route'     => 'transaction/validation-progress',
                    'resource'  => 'Transaction\Validation Progress',
                    'privilege' => 'view',
                ],
                [
                    'label'     => 'Meeting',
                    'icon'      => 'arrow-up',
                    'route'     => 'transaction/meeting',
                    'resource'  => 'Transaction\Meeting',
                    'privilege' => 'view',
                ],
            ]
        ],
        [
        'label' => 'Report',
        'icon' => 'fa fa-bar-chart-o',
        'children' =>
            [
                [
                    'label'     => 'Last Update Project',
                    'icon'      => 'files-o',
                    'route'     => 'report/project',
                    'resource'  => 'Report\Last Update Project',
                    'privilege' => 'view',
                ],
                [
                    'label'     => 'Detail History Project',
                    'icon'      => 'files-o',
                    'route'     => 'report/detail-history-project',
                    'resource'  => 'Report\Detail History Project',
                    'privilege' => 'view',
                ],
                [
                    'label'     => 'Detail History Milestone',
                    'icon'      => 'files-o',
                    'route'     => 'report/detail-history-milestone',
                    'resource'  => 'Report\Detail History Milestone',
                    'privilege' => 'view',
                ],
            ]
        ],
    ];