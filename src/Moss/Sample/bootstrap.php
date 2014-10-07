<?php
return array(
    'container' => array(
        'path' => array(
            'app' => __DIR__ . '/../src/',
            'base' => __DIR__ . '/../',
            'cache' => __DIR__ . '/../cache/',
            'compile' => __DIR__ . '/../compile/',
            'public' => __DIR__ . '/../web/',
        ),
        'view' => array(
            'component' => function (\Moss\Container\ContainerInterface $container) {
                    $view = new \Moss\View\View();
                    $view
                        ->set('request', $container->get('request'))
                        ->set('config', $container->get('config'));

                    $router = $container->get('router');
                    $view['url'] = function ($identifier = null, $arguments = array()) use ($router) {
                        return $router->make($identifier, $arguments);
                    };

                    return $view;
                }
        ),
        'flash' => array(
            'component' => function (\Moss\Container\ContainerInterface $container) {
                    return new \Moss\Http\Session\FlashBag($container->get('session'));
                },
            'shared' => true,
        ),
        'security' => array(
            'component' => function (\Moss\Container\ContainerInterface $container) {
                    $stash = new \Moss\Security\TokenStash($container->get('session'));

                    // uri to login action
                    $url = $container
                        ->get('router')
                        ->make('login');

                    $security = new \Moss\Security\Security($stash, $url);

                    // protects all actions but index and login
                    $security->registerArea(new \Moss\Security\Area('/source'));

                    // registers fake provider
                    $security->registerUserProvider(new \Moss\Sample\Provider\UserProvider());

                    return $security;
                },
            'shared' => true
        )
    ),
    'dispatcher' => array(
        'kernel.route' => array(
            function (\Moss\Container\ContainerInterface $container) {
                // tries to authenticate and authorize user
                $request = $container->get('request');
                $container->get('security')
                    ->authenticate($request)
                    ->authorize($request);
            }

        ),
        'kernel.route:exception' => array(
            function (\Moss\Container\ContainerInterface $container) {
                // if authorization or authentication fails this will redirect to login form
                $url = $container->get('security')
                    ->loginUrl();

                $response = new \Moss\Http\Response\ResponseRedirect($url, 2);
                $response->status(403);
                $response->content('Forbidden, you will be redirected... (this is an event action)');

                return $response;
            }

        ),
    ),
    'router' => array(
        'main' => array(
            'pattern' => '/',
            'controller' => 'Moss\Sample\Controller\SampleController@indexAction',
        ),
        'login' => array(
            'pattern' => '/login/',
            'controller' => 'Moss\Sample\Controller\SampleController@loginAction',
            'methods' => 'GET'
        ),
        'auth' => array(
            'pattern' => '/login/',
            'controller' => 'Moss\Sample\Controller\SampleController@authAction',
            'methods' => 'POST'
        ),
        'logout' => array(
            'pattern' => '/logout/',
            'controller' => 'Moss\Sample\Controller\SampleController@logoutAction',
        ),
        'source' => array(
            'pattern' => '/source/',
            'controller' => 'Moss\Sample\Controller\SampleController@sourceAction',
        )
    )
);