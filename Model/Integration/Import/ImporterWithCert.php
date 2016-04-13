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
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Glugox\Integration\Model\Integration\Import\ProductFactory
     */
    protected $_importProductFactory;

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
        parent::__construct($helper, $logFactory, $timezone);
        $this->_filesystem = $filesystem;
        $this->_rootDirectory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->_importProductFactory = $importProductFactory;
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
        $serviceUrl = "http://bozon.loc/kimtec-2016-04-13_02.31.56.xml";

        $this->_info("Data loading: " . $serviceUrl . "...");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serviceUrl);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_CAINFO, realpath($this->_integration->getCaFile()));
        curl_setopt($ch, CURLOPT_SSLCERT, realpath($this->_integration->getClientFile()));
        curl_setopt($ch, CURLOPT_SSLKEY, realpath($this->_integration->getKeyFile()));
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $this->_integration->getCertPass());

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 900);

        $realXml = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        $xml = null;

        if ($error) {
            $this->_info($error, true);
        } else if ($realXml) {


            // write the xml to disc
            $filename = "glugox/integration/import/" . $this->_integration->getIntegrationCode() . "-" . strftime('%Y-%m-%d_%H.%M.%S', $this->_timezone->scopeTimeStamp()) . ".xml";
            $this->_filesystem->getDirectoryWrite(DirectoryList::VAR_DIR)->writeFile($filename, $realXml);

            try {
                $xml = simplexml_load_string($realXml);
            } catch (Exception $ex) {
                $this->_info($ex->getMessage(), true);
            }

            if ($xml) {

                /**
                 * Insert data from xml into the helper tmp table 'glugox_import_products'
                 */
                $importProduct = $this->_importProductFactory->create();
                $xmlProducts = $xml->Table;
                foreach ($xmlProducts as $xmlProduct) {
                    $productName = (string) $xmlProduct->ProductName;
                    $this->_info($productName);

                    $importProduct->unsetData();
                    $importProduct->setData(array(
                        "importer_code" => $this->_integration->getIntegrationCode(),
                        "sku" => (string) $xmlProduct->ProductCode,
                        "category" => (string) $xmlProduct->ProductType,
                        "name" => (string) $xmlProduct->ProductName,
                    ));

                    $importProduct->save();
                }
            }
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
