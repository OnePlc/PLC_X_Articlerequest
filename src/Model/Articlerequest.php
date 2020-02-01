<?php
/**
 * Articlerequest.php - Articlerequest Entity
 *
 * Entity Model for Articlerequest
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
use Application\Model\CoreEntityModel;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Select;

class Articlerequest extends CoreEntityModel {
    /**
     * Request Title
     *
     * @var string $label
     * @since 1.0.0
     */
    public $label;
    /**
     * @addedtoarticle
     * @requires 1.0.5
     * @campatibleto master-dev
     */
    /**
     * Request linked Article
     *
     * @var int $article_idfs linked article (after successful match)
     * @since 1.0.0
     */
    public $article_idfs;
    /**
     * @addedtoarticleend
     */

    /**
     * Articlerequest constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @since 1.0.0
     */
    public function __construct($oDbAdapter) {
        parent::__construct($oDbAdapter);

        # Set Single Form Name
        $this->sSingleForm = 'articlerequest-single';

        # Attach Dynamic Fields to Entity Model
        $this->attachDynamicFields();
    }

    /**
     * Set Entity Data based on Data given
     *
     * @param array $aData
     * @since 1.0.0
     */
    public function exchangeArray(array $aData) {
        $this->id = !empty($aData['Articlerequest_ID']) ? $aData['Articlerequest_ID'] : 0;
        $this->label = !empty($aData['label']) ? $aData['label'] : '';

        /**
         * @addedtoarticle
         * @requires 1.0.5
         * @campatibleto master-dev
         */
        $this->article_idfs = !empty($aData['article_idfs']) ? $aData['article_idfs'] : '';
        /**
         * @addedtoarticleend
         */

        $this->updateDynamicFields($aData);
    }

    /**
     * @addedtoarticle
     * @requires 1.0.5
     * @campatibleto master-dev
     */
    /**
     * Get Matching Articles to Request
     *
     * @return array Matchings Results as Article Entities
     * @since 1.0.0
     */
    public function getMatchingResults() {

        $sCriteriaLinkMode = 'AND';

        # Init Article Table
        if(!array_key_exists('article',CoreController::$aCoreTables)) {
            CoreController::$aCoreTables['article'] = new TableGateway('article',CoreController::$oDbAdapter);
        }
        # Init Tags Table
        if(!array_key_exists('core-tag',CoreController::$aCoreTables)) {
            CoreController::$aCoreTables['core-tag'] = new TableGateway('core_tag',CoreController::$oDbAdapter);
        }
        # Init Entity Tags Table
        if(!array_key_exists('core-entity-tag',CoreController::$aCoreTables)) {
            CoreController::$aCoreTables['core-entity-tag'] = new TableGateway('core_entity_tag',CoreController::$oDbAdapter);
        }
        # Init Entity Tags Table
        if(!array_key_exists('core-entity-tag-entity',CoreController::$aCoreTables)) {
            CoreController::$aCoreTables['core-entity-tag-entity'] = new TableGateway('core_entity_tag_entity',CoreController::$oDbAdapter);
        }

        try {
            $oArticleResultTbl = CoreController::$oServiceManager->get(\OnePlace\Article\Model\ArticleTable::class);
        } catch(\RuntimeException $e) {
            throw new \RuntimeException(sprintf(
                'Could not load entity table needed for matching'
            ));
        }

        # Init Empty List
        $aMatchedArticles = [];
        $aFieldMatchingSkipList = ['state_idfs'=>true];

        $aCriterias = $this->getMatchingCriterias();

        $oFieldsDB = CoreController::$aCoreTables['core-form-field']->select(['form'=>'articlerequest-single']);
        # Match Articles by selected field values of request
        $aMatchingCrits = [];
        foreach($oFieldsDB as $oField) {
            # skip request related fields - we only want those that are linked / correspond to article
            if(array_key_exists($oField->fieldkey,$aFieldMatchingSkipList)) {
                continue;
            }
            switch($oField->type) {
                case 'select':
                    $aSingleMatches = $this->matchByAttribute($oField->fieldkey,'single');
                    $aMatchedArticles = array_merge($aMatchedArticles,$aSingleMatches);
                    if(count($aSingleMatches) > 0) {
                        if(!array_key_exists($oField->fieldkey,$aMatchingCrits)) {
                            $aMatchingCrits[$oField->fieldkey] = [];
                        }
                        foreach($aSingleMatches as $oMatch) {
                            $aMatchingCrits[$oField->fieldkey][$oMatch->getID()] = true;
                        }
                    }
                    break;
                case 'multiselect':
                    $aMultiMatches = $this->matchByAttribute(str_replace(['ies'],['y'],$oField->fieldkey),'multi');
                    $aMatchedArticles = array_merge($aMatchedArticles,$aMultiMatches);
                    if(count($aMultiMatches) > 0) {
                        if(!array_key_exists($oField->fieldkey,$aMultiMatches)) {
                            $aMatchingCrits[$oField->fieldkey] = [];
                        }
                        foreach($aMultiMatches as $oMatch) {
                            $aMatchingCrits[$oField->fieldkey][$oMatch->getID()] = true;
                        }
                    }
                    break;
                default:
                    break;
            }
        }

        # sort matchings by id to prevent duplicates from different matchings
        $aMatchingsByID = [];
        foreach($aMatchedArticles as $oSortMatch) {
            $aMatchingsByID[$oSortMatch->getID()] = $oSortMatch;
        }

        # if and link is active, show only articles that match ALL criterias
        if($sCriteriaLinkMode == 'AND') {
            foreach ($aMatchingsByID as $oTempMatch) {
                foreach (array_keys($aMatchingCrits) as $sMatchCrit) {
                    if (!array_key_exists($oTempMatch->getID(), $aMatchingCrits[$sMatchCrit])) {
                        unset($aMatchingsByID[$oTempMatch->getID()]);
                    }
                }
            }
        }

        # apply final matchings
        $aMatchedArticles = $aMatchingsByID;

        # enforce state on articles
        if(count($aMatchedArticles) > 0) {
            # Check if state tag is present
            $sTagKey = 'state';
            $oTag = CoreController::$aCoreTables['core-tag']->select(['tag_key'=>$sTagKey]);
            if(count($oTag) > 0) {
                # check if enforce state option for request is active
                $sState = CoreController::$aGlobalSettings['articlerequest-enforce-state'];
                if($sState != '') {
                    # enforce state for results
                    $aEnforcedMatches = [];
                    $oTag = $oTag->current();
                    $oEntityTag = CoreController::$aCoreTables['core-entity-tag']->select(['tag_value' => $sState, 'tag_idfs' => $oTag->Tag_ID]);

                    # check if state exists for entity
                    if (count($oEntityTag) > 0) {
                        $oEntityTag = $oEntityTag->current();
                        # compare state for all matches, only add matching
                        foreach (array_keys($aMatchedArticles) as $sMatchKey) {
                            $oMatch = $aMatchedArticles[$sMatchKey];
                            if ($oMatch->getSelectFieldID('state_idfs') == $oEntityTag->Entitytag_ID) {
                                $aEnforcedMatches[] = $oMatch;
                            }
                        }
                    }
                    # return curated results
                    $aMatchedArticles = $aEnforcedMatches;
                }
            }
        }

        return $aMatchedArticles;
    }

    private function matchByAttribute($sTagKey,$sTagLinkType = 'multi') {
        try {
            $oArticleResultTbl = CoreController::$oServiceManager->get(\OnePlace\Article\Model\ArticleTable::class);
        } catch(\RuntimeException $e) {
            throw new \RuntimeException(sprintf(
                'Could not load entity table needed for matching'
            ));
        }
        $aMatchedArticles = [];
        # Match Article by Category - only if category tag is found
        $oTag = CoreController::$aCoreTables['core-tag']->select(['tag_key'=>str_replace(['_idfs'],[''],$sTagKey)]);
        if(count($oTag)) {
            $oTag = $oTag->current();
            # 1. Get all Categories linked to this request
            $oMyCats = (object)[];
            if($sTagLinkType == 'multi') {
                $oCategorySel = new Select(CoreController::$aCoreTables['core-entity-tag-entity']->getTable());
                $oCategorySel->join(['cet'=>'core_entity_tag'],'cet.Entitytag_ID = core_entity_tag_entity.entity_tag_idfs');
                $oCategorySel->where(['entity_idfs'=>$this->getID(),'cet.tag_idfs = '.$oTag->Tag_ID,'entity_type'=>'articlerequest']);
                $oMyCats = CoreController::$aCoreTables['core-entity-tag']->selectWith($oCategorySel);

                if(count($oMyCats) > 0) {
                    # Loop over all matched categories
                    foreach($oMyCats as $oMyCat) {
                        # Find article with the same category
                        $oMatchedArtsByCat = CoreController::$aCoreTables['core-entity-tag-entity']->select(['entity_tag_idfs'=>$oMyCat->Entitytag_ID,'entity_type'=>'article']);
                        if(count($oMatchedArtsByCat) > 0) {
                            foreach($oMatchedArtsByCat as $oMatchRow) {
                                $oMatchObj = $oArticleResultTbl->getSingle($oMatchRow->entity_idfs);
                                $oMatchObj->sMatchedBy = $sTagKey;
                                $aMatchedArticles[$oMatchObj->getID()] = $oMatchObj;
                            }
                        }
                    }
                }
            } else {
                $oMatchedArtsBySingle = $oArticleResultTbl->fetchAll(false,[$sTagKey=>$this->getSelectFieldID($sTagKey)]);
                if(count($oMatchedArtsBySingle) > 0) {
                    foreach($oMatchedArtsBySingle as $oMatchObj) {
                        $oMatchObj->sMatchedBy = $sTagKey;
                        $aMatchedArticles[$oMatchObj->getID()] = $oMatchObj;
                    }
                }
            }
        }

        return $aMatchedArticles;
    }

    /**
     * Get Matching Criterias for Results
     *
     * @return array list with criterias
     * @since 1.0.0
     */
    public function getMatchingCriterias() {
        $aMatchingCriterias = [];

        # Init Criterias Table
        if(!array_key_exists('article-request-criteria',CoreController::$aCoreTables)) {
            CoreController::$aCoreTables['article-request-criteria'] = new TableGateway('articlerequest_criteria',CoreController::$oDbAdapter);
        }

        $oCriteriasFromDB = CoreController::$aCoreTables['article-request-criteria']->select();
        foreach($oCriteriasFromDB as $oCrit) {
            $aMatchingCriterias[$oCrit->criteria_entity_key] = (array)$oCrit;
        }

        return $aMatchingCriterias;
    }
    /**
     * @addedtoarticleend
     */
}