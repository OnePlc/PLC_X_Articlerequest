<?php
/**
 * ArticlerequestController.php - Main Controller
 *
 * Main Controller Articlerequest Module
 *
 * @category Controller
 * @package Articlerequest
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

namespace OnePlace\Articlerequest\Controller;

use Application\Controller\CoreEntityController;
use Application\Model\CoreEntityModel;
use OnePlace\Articlerequest\Model\Articlerequest;
use OnePlace\Articlerequest\Model\ArticlerequestTable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\AdapterInterface;

class ArticlerequestController extends CoreEntityController {
    /**
     * Articlerequest Table Object
     *
     * @since 1.0.0
     */
    protected $oTableGateway;

    /**
     * ArticlerequestController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param ArticlerequestTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,ArticlerequestTable $oTableGateway,$oServiceManager) {
        $this->oTableGateway = $oTableGateway;
        $this->sSingleForm = 'articlerequest-single';
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);

        if($oTableGateway) {
            # Attach TableGateway to Entity Models
            if(!isset(CoreEntityModel::$aEntityTables[$this->sSingleForm])) {
                CoreEntityModel::$aEntityTables[$this->sSingleForm] = $oTableGateway;
            }
        }
    }

    /**
     * Articlerequest Index
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function indexAction() {
        # You can just use the default function and customize it via hooks
        # or replace the entire function if you need more customization
        return $this->generateIndexView('articlerequest');
    }

    /**
     * Articlerequest Add Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function addAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * articlerequest-add-before (before show add form)
         * articlerequest-add-before-save (before save)
         * articlerequest-add-after-save (after save)
         */
        return $this->generateAddView('articlerequest');
    }

    /**
     * Articlerequest Edit Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function editAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * articlerequest-edit-before (before show edit form)
         * articlerequest-edit-before-save (before save)
         * articlerequest-edit-after-save (after save)
         */
        return $this->generateEditView('articlerequest');
    }

    /**
     * Articlerequest View Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function viewAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * articlerequest-view-before
         */
        return $this->generateViewView('articlerequest');
    }
}
