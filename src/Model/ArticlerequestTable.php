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
     * @return mixed
     * @since 1.0.0
     */
    public function getSingle($id) {
        return $this->getSingleEntity($id,'Articlerequest_ID');
    }

    /**
     * Save Articlerequest Entity
     *
     * @param Articlerequest $oArticlerequest
     * @return int Articlerequest ID
     * @since 1.0.0
     */
    public function saveSingle(Articlerequest $oArticlerequest) {
        $aData = [
            'label' => $oArticlerequest->label,
        ];

        $aData = $this->attachDynamicFields($aData,$oArticlerequest);

        $id = (int) $oArticlerequest->id;

        if ($id === 0) {
            # Add Metadata
            $aData['created_by'] = CoreController::$oSession->oUser->getID();
            $aData['created_date'] = date('Y-m-d H:i:s',time());
            $aData['modified_by'] = CoreController::$oSession->oUser->getID();
            $aData['modified_date'] = date('Y-m-d H:i:s',time());

            # Insert Articlerequest
            $this->oTableGateway->insert($aData);

            # Return ID
            return $this->oTableGateway->lastInsertValue;
        }

        # Check if Articlerequest Entity already exists
        try {
            $this->getSingle($id);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf(
                'Cannot update articlerequest with identifier %d; does not exist',
                $id
            ));
        }

        # Update Metadata
        $aData['modified_by'] = CoreController::$oSession->oUser->getID();
        $aData['modified_date'] = date('Y-m-d H:i:s',time());

        # Update Articlerequest
        $this->oTableGateway->update($aData, ['Articlerequest_ID' => $id]);

        return $id;
    }

    /**
     * Generate new single Entity
     *
     * @return Articlerequest
     * @since 1.0.7
     */
    public function generateNew() {
        return new Articlerequest($this->oTableGateway->getAdapter());
    }
}