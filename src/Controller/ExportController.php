<?php
/**
 * ExportController.php - Articlerequest Export Controller
 *
 * Main Controller for Articlerequest Export
 *
 * @category Controller
 * @package Articlerequest
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Articlerequest\Controller;

use Application\Controller\CoreController;
use Application\Controller\CoreExportController;
use OnePlace\Articlerequest\Model\ArticlerequestTable;
use Laminas\Db\Sql\Where;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\View\Model\ViewModel;


class ExportController extends CoreExportController
{
    /**
     * ApiController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param ArticlerequestTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,ArticlerequestTable $oTableGateway,$oServiceManager) {
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);
    }


    /**
     * Dump Articlerequests to excel file
     *
     * @return ViewModel
     * @since 1.0.0
     */
    public function dumpAction() {
        $this->layout('layout/json');

        # Use Default export function
        $aViewData = $this->exportData('Articlerequests','articlerequest');

        # return data to view (popup)
        return new ViewModel($aViewData);
    }
}