<?php
/**
 * ArticlerequestTable.php - Articlerequest Table
 *
 * Table Model for Articlerequest
 *
 * @category Model
 * @package Articlerequest
 * @author Verein onePlace
 * @copyright (C) 2020 Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Articlerequest\Model;

use Application\Controller\CoreController;
use Application\Model\CoreEntityTable;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;

class ArticlerequestTable extends CoreEntityTable {

    /**
     * ArticlerequestTable constructor.
     *
     * @param TableGateway $tableGateway
     * @since 1.0.0
     */
    public function __construct(TableGateway $tableGateway) {
        parent::__construct($tableGateway);

        # Set Single Form Name
        $this->sSingleForm = 'articlerequest-single';
    }

    /**
     * Get Articlerequest Entity
     *
     * @param int $id
     * @param string $sKey
     * @return mixed
     * @since 1.0.0
     */
    public function getSingle($id,$sKey = 'Articlerequest_ID') {
        # Use core function
        return $this->getSingleEntity($id,$sKey);
    }

    /**
     * Save Articlerequest Entity
     *
     * @param Articlerequest $oArticlerequest
     * @return int Articlerequest ID
     * @since 1.0.0
     */
    public function saveSingle(Articlerequest $oArticlerequest) {
        $aDefaultData = [
            'label' => $oArticlerequest->label,
        ];

        return $this->saveSingleEntity($oArticlerequest,'Articlerequest_ID',$aDefaultData);
    }

    /**
     * Generate new single Entity
     *
     * @return Articlerequest
     * @since 1.0.0
     */
    public function generateNew() {
        return new Articlerequest($this->oTableGateway->getAdapter());
    }
}