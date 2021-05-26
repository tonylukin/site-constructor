<?php

declare(strict_types=1);

namespace app\services\siteView;

class Sape
{
    private const PATH_PREFIX = 'sape';

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
        $hostRelatedSapeFile = $this->createFileStructure();
        require_once($hostRelatedSapeFile);

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

    private function createFileStructure(): string
    {
        if (!\defined('_SAPE_USER')){
            \define('_SAPE_USER', '5ad1630fb304dd653bd8fea1c76343ec');
        }

        $host = \Yii::$app->request->hostName;
        $sapeFile = realpath($_SERVER['DOCUMENT_ROOT']. '/' ._SAPE_USER . '/sape.php');
        $hostRelatedSapePath = $_SERVER['DOCUMENT_ROOT'] . '/' . self::PATH_PREFIX . '/' . $host . '/' . _SAPE_USER;
        $hostRelatedSapeFile = $hostRelatedSapePath . '/sape.php';
        if (file_exists($hostRelatedSapeFile)) {
            return $hostRelatedSapeFile;
        }

        if (!mkdir($hostRelatedSapePath, 0777, true) && !is_dir($hostRelatedSapePath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $hostRelatedSapePath));
        }

        copy($sapeFile, $hostRelatedSapeFile);
        chmod($hostRelatedSapeFile, 0777);

        return $hostRelatedSapeFile;
    }
}
