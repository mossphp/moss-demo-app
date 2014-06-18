<?php
namespace Moss\Sample\Controller;

use Moss\Http\Response\Response;
use Moss\Http\Response\ResponseRedirect;
use Moss\Kernel\App;
use Moss\Security\AuthenticationException;

/**
 * Class SampleController
 *
 * @package Moss\Sample
 */
class SampleController
{
    protected $app;


    /**
     * Constructor
     *
     * @param App $moss
     */
    public function __construct(App $moss)
    {
        $this->app = & $moss;
    }

    /**
     * Sample method, displays link to controller source
     *
     * @return Response
     */
    public function indexAction()
    {
        $content = $this->app->get('view')
            ->template('Moss:Sample:index')
            ->set('method', __METHOD__)
            ->render();

        return new Response($content);
    }

    /**
     * Login form
     *
     * @return Response
     */
    public function loginAction()
    {
        return new Response($this->form());
    }

    /**
     * Logging action
     *
     * @return Response
     */
    public function authAction()
    {
        try {
            $this->app->get('security')
                ->tokenize($this->app->request->body->all());

            return new ResponseRedirect($this->app->router->make('source'));
        } catch (AuthenticationException $e) {
            $this->app->get('flash')
                ->add($e->getMessage(), 'error');

            return new Response($this->form(), 401);
        }
    }

    /**
     * Returns rendered login form
     *
     * @return string
     */
    protected function form()
    {
        return $this->app->get('view')
            ->template('Moss:Sample:login')
            ->set('method', __METHOD__)
            ->set('flash', $this->app->get('flash'))
            ->render();
    }

    /**
     * Logout
     *
     * @return ResponseRedirect
     */
    public function logoutAction()
    {
        $this->app->get('security')
            ->destroy();

        $response = new ResponseRedirect($this->app->router->make('main'), 5);
        $response->status(403);
        $response->content('Logged out, you will be redirected... (back to login form)');

        return $response;
    }

    /**
     * Displays controllers and bootstrap source
     *
     * @return Response
     */
    public function sourceAction()
    {
        $content = $this->app->get('view')
            ->template('Moss:Sample:source')
            ->set('method', __METHOD__)
            ->set('controller', highlight_file(__FILE__, true))
            ->set('bootstrap', highlight_file(__DIR__ . '/../bootstrap.php', true))
            ->render();

        return new Response($content);
    }
}