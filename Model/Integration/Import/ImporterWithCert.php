<?php

/*
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Model\Integration\Import;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Description of ImporterWithCert
 *
 * @author Eko
 */
class ImporterWithCert extends Importer {

    /**
     * @var \Magento\Framework\Filesystem\Directory\Read
     */
    protected $_rootDirectory;

    /**
     *
     * @param \Glugox\Integration\Helper\Data $helper
     * @param \Glugox\Integration\Model\Integration\LogFactory $logFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     */
    public function __construct(
            \Glugox\Integration\Helper\Data $helper,
            \Glugox\Integration\Model\Integration\LogFactory $logFactory,
            \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
            \Magento\Framework\Filesystem $filesystem,
            \Glugox\Integration\Model\Integration\Import\ProductFactory $importProductFactory
    ) {
        parent::__construct($helper, $logFactory, $timezone, $filesystem, $importProductFactory);

        $this->_rootDirectory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);

    }


    /**
     * Runs the data import
     */
    public function import() {

        $this->_info("[ImporterWithCert]");
        $import = parent::import();
    }


    /**
     * Runs the data import
     *
     * @return boolaen
     */
    protected function _import() {


        $serviceUrl = $this->_integration->getServiceUrl();
        $this->_info("Data loading (secure): " . $serviceUrl . "...");
        $ch = $this->_buildCurl($serviceUrl);

        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_CAINFO, realpath($this->_integration->getCaFile()));
        curl_setopt($ch, CURLOPT_SSLCERT, realpath($this->_integration->getClientFile()));
        curl_setopt($ch, CURLOPT_SSLKEY, realpath($this->_integration->getKeyFile()));
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $this->_integration->getCertPass());

        $realXml = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        $xml = null;

        if ($error) {
            $this->_info($error, true);
        } else if ($realXml) {

            $this->_importXML($realXml);
        }
    }


    /**
     *
     * @return type
     */
    protected function _validate() {

        $caFile = $this->_integration->getCaFile();
        $clientFile = $this->_integration->getClientFile();
        $keyFile = $this->_integration->getKeyFile();
        $certPass = $this->_integration->getCertPass();

        if (empty($caFile) || !$this->_rootDirectory->isExist($caFile)) {
            $msg = "CA FILE does not exist: " . $this->_rootDirectory->getAbsolutePath($caFile);
            $this->_info($msg, true);
        }
        if (empty($clientFile) || !$this->_rootDirectory->isExist($clientFile)) {
            $msg = "CLIENT FILE does not exist: " . $this->_rootDirectory->getAbsolutePath($clientFile);
            $this->_info($msg, true);
        }
        if (empty($keyFile) || !$this->_rootDirectory->isExist($keyFile)) {
            $msg = "KEY FILE does not exist: " . $this->_rootDirectory->getAbsolutePath($keyFile);
            $this->_info($msg, true);
        }
        if (empty($certPass)) {
            $msg = "Cert pass must not be empty!";
            $this->_info($msg, true);
        }

        return parent::_validate();
    }


}
