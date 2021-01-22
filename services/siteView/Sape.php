<?php

declare(strict_types=1);

namespace app\services\siteView;

class Sape
{
    /**
     * @var \SAPE_context
     */
    private $sapeContext;
    /**
     * @var \SAPE_client
     */
    private $sapeClient;

    public function __construct()
    {
        // sape code
        if (!\defined('_SAPE_USER')){
            \define('_SAPE_USER', '5ad1630fb304dd653bd8fea1c76343ec');
        }
        require_once(realpath($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php'));
        $this->sapeContext = new \SAPE_context();
        ob_start([&$this->sapeContext,'replace_in_page']);

        $this->sapeClient = new \SAPE_client();
    }

    public function getContextInstance(): \SAPE_context
    {
        return $this->sapeContext;
    }

    public function getClientInstance(): \SAPE_client
    {
        return $this->sapeClient;
    }
}
