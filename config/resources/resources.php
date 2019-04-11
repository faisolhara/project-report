<?php

return [
        'master' => [
            'Master\Access Control' => ['view', 'update'],
        ],
        'transaction' => [
            'Transaction\Project' => ['view', 'add-progress-technical', 'add-progress-marketing', 'add-remove-milestone-technical', 'add-remove-milestone-marketing'],
            'Transaction\Update Progress' => ['view', 'update', 'delete'],
            'Transaction\Validation Progress' => ['view'],
            'Transaction\Meeting' => ['view', 'add', 'update', 'delete'],
        ],
        'report' => [
            'Report\Last Update Project' => ['view'],
            'Report\Detail History Project' => ['view'],
            'Report\Detail History Milestone' => ['view'],
        ],
    ];
    