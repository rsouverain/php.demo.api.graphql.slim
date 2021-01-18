<?php

namespace App\Boilerplate;


use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AppContext
 * @package App\Boilerplate
 */
class AppContext
{
    /** @var array|null */
    protected $authenticatedUserAccount = null; // todo UserAccount Model

    /** @var ServerRequestInterface|null */
    protected $request = null;

    /** @var AppContext|null */
    private static $instance;

    /**
     * Singleton
     * @return AppContext|static|null
     */
    public static function getInstance ()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    /**
     * @return array|null
     */
    public function getAuthenticatedUserAccount(): ?array
    {
        return $this->authenticatedUserAccount;
    }

    /**
     * @param array|null $authenticatedUserAccount
     * @return AppContext
     */
    public function setAuthenticatedUserAccount(?array $authenticatedUserAccount): AppContext
    {
        $this->authenticatedUserAccount = $authenticatedUserAccount;
        return $this;
    }

    /**
     * @return ServerRequestInterface|null
     */
    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface|null $request
     * @return AppContext
     * @throws \Exception
     */
    public function setRequest(?ServerRequestInterface $request): AppContext
    {
        if ($this->request !== null) {
            throw new \Exception('Request is already set !');
        }
        $this->request = $request;
        return $this;
    }


}
