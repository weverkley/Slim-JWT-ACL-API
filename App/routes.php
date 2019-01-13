<?php
// default page render
$app->group('', function () {
    $this->any('/', 'HomeController:defaultPage');
    // $this->any('/{name:[A-Za-z]+}', 'HomeController:defaultPage');
});

// v1 api routes
$app->group('/v1', function () {

    // Authentication
    $this->group('/auth', function () {
        $this->post('/user', 'AuthController:authenticate');
    });

    // Users
    $this->group('/users', function () {
        $this->get('', 'UserController:list')->setName('users.list');
        $this->post('', 'UserController:create')->setName('users.create');
    })->add('ACLMiddleware');

});
