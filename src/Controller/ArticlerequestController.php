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

use Application\Controller\CoreController;
use Application\Model\CoreEntityModel;
use OnePlace\Articlerequest\Model\Articlerequest;
use OnePlace\Articlerequest\Model\ArticlerequestTable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\AdapterInterface;

class ArticlerequestController extends CoreController {
    /**
     * Articlerequest Table Object
     *
     * @since 1.0.0
     */
    private $oTableGateway;

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
        # Set Layout based on users theme
        $this->setThemeBasedLayout('articlerequest');

        # Check license
        if(!$this->checkLicense('articlerequest')) {
            $this->flashMessenger()->addErrorMessage('You have no active license for articlerequest');
            $this->redirect()->toRoute('home');
        }

        # Add Buttons for breadcrumb
        $this->setViewButtons('articlerequest-index');

        # Set Table Rows for Index
        $this->setIndexColumns('articlerequest-index');

        # Get Paginator
        $oPaginator = $this->oTableGateway->fetchAll(true);
        $iPage = (int) $this->params()->fromQuery('page', 1);
        $iPage = ($iPage < 1) ? 1 : $iPage;
        $oPaginator->setCurrentPageNumber($iPage);
        $oPaginator->setItemCountPerPage(3);

        # Log Performance in DB
        $aMeasureEnd = getrusage();
        $this->logPerfomance('articlerequest-index',$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"utime"),$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"stime"));

        return new ViewModel([
            'sTableName'=>'articlerequest-index',
            'aItems'=>$oPaginator,
        ]);
    }

    /**
     * Articlerequest Add Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function addAction() {
        # Set Layout based on users theme
        $this->setThemeBasedLayout('articlerequest');

        # Check license
        if(!$this->checkLicense('articlerequest')) {
            $this->flashMessenger()->addErrorMessage('You have no active license for articlerequest');
            $this->redirect()->toRoute('home');
        }

        # Get Request to decide wether to save or display form
        $oRequest = $this->getRequest();

        # Display Add Form
        if(!$oRequest->isPost()) {
            # Add Buttons for breadcrumb
            $this->setViewButtons('articlerequest-single');

            # Load Tabs for View Form
            $this->setViewTabs($this->sSingleForm);

            # Load Fields for View Form
            $this->setFormFields($this->sSingleForm);

            # Log Performance in DB
            $aMeasureEnd = getrusage();
            $this->logPerfomance('articlerequest-add',$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"utime"),$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"stime"));

            return new ViewModel([
                'sFormName' => $this->sSingleForm,
            ]);
        }

        # Get and validate Form Data
        $aFormData = $this->parseFormData($_REQUEST);

        # Save Add Form
        $oArticlerequest = new Articlerequest($this->oDbAdapter);
        $oArticlerequest->exchangeArray($aFormData);
        $iArticlerequestID = $this->oTableGateway->saveSingle($oArticlerequest);
        $oArticlerequest = $this->oTableGateway->getSingle($iArticlerequestID);

        # Save Multiselect
        $this->updateMultiSelectFields($_REQUEST,$oArticlerequest,'articlerequest-single');

        # Log Performance in DB
        $aMeasureEnd = getrusage();
        $this->logPerfomance('articlerequest-save',$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"utime"),$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"stime"));

        # Display Success Message and View New Articlerequest
        $this->flashMessenger()->addSuccessMessage('Articlerequest successfully created');
        return $this->redirect()->toRoute('articlerequest',['action'=>'view','id'=>$iArticlerequestID]);
    }

    /**
     * Articlerequest Edit Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function editAction() {
        # Set Layout based on users theme
        $this->setThemeBasedLayout('articlerequest');

        # Check license
        if(!$this->checkLicense('articlerequest')) {
            $this->flashMessenger()->addErrorMessage('You have no active license for articlerequest');
            $this->redirect()->toRoute('home');
        }

        # Get Request to decide wether to save or display form
        $oRequest = $this->getRequest();

        # Display Edit Form
        if(!$oRequest->isPost()) {

            # Get Articlerequest ID from URL
            $iArticlerequestID = $this->params()->fromRoute('id', 0);

            # Try to get Articlerequest
            try {
                $oArticlerequest = $this->oTableGateway->getSingle($iArticlerequestID);
            } catch (\RuntimeException $e) {
                echo 'Articlerequest Not found';
                return false;
            }

            # Attach Articlerequest Entity to Layout
            $this->setViewEntity($oArticlerequest);

            # Add Buttons for breadcrumb
            $this->setViewButtons('articlerequest-single');

            # Load Tabs for View Form
            $this->setViewTabs($this->sSingleForm);

            # Load Fields for View Form
            $this->setFormFields($this->sSingleForm);

            # Log Performance in DB
            $aMeasureEnd = getrusage();
            $this->logPerfomance('articlerequest-edit',$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"utime"),$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"stime"));

            return new ViewModel([
                'sFormName' => $this->sSingleForm,
                'oArticlerequest' => $oArticlerequest,
            ]);
        }

        $iArticlerequestID = $oRequest->getPost('Item_ID');
        $oArticlerequest = $this->oTableGateway->getSingle($iArticlerequestID);

        # Update Articlerequest with Form Data
        $oArticlerequest = $this->attachFormData($_REQUEST,$oArticlerequest);

        # Save Articlerequest
        $iArticlerequestID = $this->oTableGateway->saveSingle($oArticlerequest);

        $this->layout('layout/json');

        $aFormData = $this->parseFormData($_REQUEST);

        # Save Multiselect
        $this->updateMultiSelectFields($aFormData,$oArticlerequest,'articlerequest-single');

        # Log Performance in DB
        $aMeasureEnd = getrusage();
        $this->logPerfomance('articlerequest-save',$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"utime"),$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"stime"));

        # Display Success Message and View New User
        $this->flashMessenger()->addSuccessMessage('Articlerequest successfully saved');
        return $this->redirect()->toRoute('articlerequest',['action'=>'view','id'=>$iArticlerequestID]);
    }

    /**
     * Articlerequest View Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function viewAction() {
        # Set Layout based on users theme
        $this->setThemeBasedLayout('articlerequest');

        # Check license
        if(!$this->checkLicense('articlerequest')) {
            $this->flashMessenger()->addErrorMessage('You have no active license for articlerequest');
            $this->redirect()->toRoute('home');
        }

        # Get Articlerequest ID from URL
        $iArticlerequestID = $this->params()->fromRoute('id', 0);

        # Try to get Articlerequest
        try {
            $oArticlerequest = $this->oTableGateway->getSingle($iArticlerequestID);
        } catch (\RuntimeException $e) {
            echo 'Articlerequest Not found';
            return false;
        }

        # Attach Articlerequest Entity to Layout
        $this->setViewEntity($oArticlerequest);

        # Add Buttons for breadcrumb
        $this->setViewButtons('articlerequest-view');

        # Load Tabs for View Form
        $this->setViewTabs($this->sSingleForm);

        # Load Fields for View Form
        $this->setFormFields($this->sSingleForm);

        /**
         * @addedtoarticle
         * @requires 1.0.5
         * @campatibleto master-dev
         */
        $aPartialData = [
            'aMatchingResults'=>$oArticlerequest->getMatchingResults(),
            'aViewCriterias' =>$oArticlerequest->getMatchingCriterias(),
        ];
        $this->setPartialData('matching',$aPartialData);
        /**
         * @addedtoarticleend
         */

        # Log Performance in DB
        $aMeasureEnd = getrusage();
        $this->logPerfomance('articlerequest-view',$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"utime"),$this->rutime($aMeasureEnd,CoreController::$aPerfomanceLogStart,"stime"));

        return new ViewModel([
            'sFormName'=>$this->sSingleForm,
            'oArticlerequest'=>$oArticlerequest,
        ]);
    }

    /**
     * @addedtoarticle
     * @requires 1.0.5
     * @campatibleto master-dev
     */
    /**
     * Close Request as successful
     *
     * @since 1.0.0
     */
    public function successAction() {
        $aInfo = explode('-',$this->params()->fromRoute('id','0-0'));
        $iRequestID = $aInfo[0];
        $iArticleID = $aInfo[1];

        # Check license
        if(!$this->checkLicense('articlerequest')) {
            $this->flashMessenger()->addErrorMessage('You have no active license for articlerequest');
            $this->redirect()->toRoute('home');
        }

        try {
            $oArticleTable = CoreController::$oServiceManager->get(\OnePlace\Article\Model\ArticleTable::class);
        } catch(\RuntimeException $e) {
            echo 'could not load article table';
            return false;
        }

        # check if state tag is active
        $oTag = CoreController::$aCoreTables['core-tag']->select(['tag_key'=>'state']);
        if(count($oTag) > 0) {
            $oTagState = $oTag->current();
            # check if we find success state tag for article request
            $oEntityTagRequest = CoreController::$aCoreTables['core-entity-tag']->select(['tag_value'=>'success','tag_idfs'=>$oTagState->Tag_ID,'entity_form_idfs'=>'articlerequest-single']);
            if(count($oEntityTagRequest) > 0) {
                $oEntityTagSuccess = $oEntityTagRequest->current();
                $this->oTableGateway->updateAttribute('state_idfs',$oEntityTagSuccess->Entitytag_ID,'Articlerequest_ID',$iRequestID);
                $this->oTableGateway->updateAttribute('article_idfs',$iArticleID,'Articlerequest_ID',$iRequestID);
            }
            # check if we find sold state tag for article
            $oEntityTagArticle = CoreController::$aCoreTables['core-entity-tag']->select(['tag_value'=>'sold','tag_idfs'=>$oTagState->Tag_ID,'entity_form_idfs'=>'article-single']);
            if(count($oEntityTagArticle) > 0) {
                $oEntityTagSold = $oEntityTagArticle->current();
                $oArticleTable->updateAttribute('state_idfs',$oEntityTagSold->Entitytag_ID,'Article_ID',$iRequestID);
            }
        }

        # Display Success Message and View New Articlerequest
        $this->flashMessenger()->addSuccessMessage('Articlerequest successfully closed');
        return $this->redirect()->toRoute('articlerequest',['action'=>'view','id'=>$iRequestID]);
    }
    /**
     * @addedtoarticleend
     */
}
