<?php

// custom.php file returd default configuration setting of layouts
return [
    'theme' => [
        'mainLayoutType' => 'vertical-menu-boxicons',
        //Options:vertical-menu,horizontal-menu,vertical-menu-boxicons, default(vertical-menu)
        'theme' => 'semi-dark',
        //light(default),dark,semi-dark (note: Horizontal-menu not applicable for semi-dark)
        'isContentSidebar' => false,
        // Options: True and false(default) (There are two page layout with content-sidebar and without sidebar)
        'pageHeader' => false,
        //options:Boolean: false(default), true (Page Header for Breadcrumbs) Warning:if page header true need to define a breadcrumbs in controller
        'bodyCustomClass' => '',
        //any custom class can be pass
        'navbarBgColor' => 'bg-white',
        //Options:bg-white(default for vertical-menu),bg-primary(default horizontal-menu), bg-success,bg-danger,bg-info,bg-warning,bg-dark.(Note:color only visible when you scroll down)
        'navbarType' => 'hidden',
        // options:fixed,static,hidden (note: Horizontal-menu template only support fixed and static)
        'isMenuCollapsed' => false,
        // options:true or false(default)  Warning:this option is not applicable for horizontal-menu template
        'footerType' => 'static',
        //options:fixed,static,hidden
        'templateTitle' => '',
        //template Title can be changed, default(Frest)
        'isCardShadow' => true,
        // Option: true(default) and false ( remove card shadow)
        'isScrollTop' => false,
        // Option: true and false (Hide Scroll To Top)
        'defaultLanguage' => 'zh-CN',
        //set your default language Options: en(default),pt,fr,de
        'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'),
        // Page direction
    ],
    'upload' => [
        'image' => [
            'size' => env('FILE_SIZE_LIMIT', 12000000), // 12MB
            'mime_type' => ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'application/mp4'],
        ],
        'excel' => [
            'size' => env('FILE_SIZE_LIMIT', 4194304), // 4MB
            'mime_type' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        ],
    ],
    'login_secret' => env('LOGIN_SECRET', ''),
];
