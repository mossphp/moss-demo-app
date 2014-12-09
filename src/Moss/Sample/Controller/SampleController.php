<?php
namespace Moss\Sample\Controller;

use Moss\Http\Response\Response;
use Moss\Http\Response\ResponseRedirect;
use Moss\Http\Session\SessionInterface;
use Moss\Kernel\App;
use Moss\Security\AuthenticationException;
use Moss\View\ViewInterface;

/**
 * Class SampleController
 *
 * @package Moss\Sample
 */
class SampleController
{
    const VIEW_PHP = 'php';
    const VIEW_TWIG = 'twig';

    /**
     * @var App
     */
    protected $app;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var ViewInterface
     */
    protected $view;


    /**
     * Constructor
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        $this->session = $this->app->get('session');

        if($this->app->request()->query()->has('view')) {
            $view = $this->app->request()->query()->get('view');
            $this->session->set('view', $view == self::VIEW_TWIG ? self::VIEW_TWIG : self::VIEW_PHP);
        }

        $this->view = $this->session->get('view') == self::VIEW_TWIG ? $app->get('twigView') : $app->get('phpView');
    }

    /**
     * Sample method, displays link to controller source
     *
     * @return Response
     */
    public function indexAction()
    {
        $content = $this->view
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
                ->tokenize($this->app->request()->body()->all());

            return new ResponseRedirect($this->app->router()->make('source'));
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
        return $this->view
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

        $response = new ResponseRedirect($this->app->router()->make('main'), 5);
        $response->status(403);
        $response->content('Logged out, you will be redirected... (back to index)');

        return $response;
    }

    /**
     * Displays controllers and bootstrap source
     *
     * @return Response
     */
    public function sourceAction()
    {
        $content = $this->view
            ->template('Moss:Sample:source')
            ->set('method', __METHOD__)
            ->set('controller', highlight_file(__FILE__, true))
            ->set('bootstrap', highlight_file(__DIR__ . '/../bootstrap.php', true))
            ->render();

        return new Response($content);
    }
}
