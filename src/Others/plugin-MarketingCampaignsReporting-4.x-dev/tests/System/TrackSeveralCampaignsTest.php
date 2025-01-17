<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * Based on code from AdvancedCampaignReporting plugin by Piwik PRO released under GPL v3 or later: https://github.com/PiwikPRO/plugin-AdvancedCampaignReporting
 */
namespace Piwik\Plugins\MarketingCampaignsReporting\tests\System;

use Piwik\Cache;
use Piwik\Plugin\Manager;
use Piwik\Plugins\MarketingCampaignsReporting\tests\Fixtures\TrackAdvancedCampaigns;
use Piwik\Tests\Framework\TestCase\SystemTestCase;
use Piwik\Version;

/**
 * @group MarketingCampaignsReporting
 * @group Plugins
 */
class TrackSeveralCampaignsTest extends SystemTestCase
{
    /**
     * @var TrackAdvancedCampaigns
     */
    public static $fixture = null; // initialized below class definition

    public static function getOutputPrefix()
    {
        return '';
    }

    public static function getPathToTestDirectory()
    {
        return dirname(__FILE__);
    }

    /**
     * @dataProvider getApiForTesting
     * @group        TrackSeveralCampaignsTest
     */
    public function testApi($api, $params)
    {
        $this->runApiTests($api, $params);
    }

    /**
     * Old API is disabled if plugin is enabled
     * This test aims to check if campaigns are still
     * correctly fetch with the old api if plugin is disabled
     *
     * @dataProvider getReferrerApiForTesting
     * @group        TrackSeveralCampaignsTest
     */
    public function testAnotherApi($api, $params)
    {
        Manager::getInstance()->unloadPlugin('MarketingCampaignsReporting');
        Cache::flushAll();
        $this->runApiTests($api, $params);
        Manager::getInstance()->loadPlugin('MarketingCampaignsReporting');
        Cache::flushAll();
    }


    public function getApiForTesting()
    {
        $dateWithPluginEnabled = self::$fixture->dateTimeWithPluginEnabled;
        $dateTime              = self::$fixture->dateTime;

        if (version_compare(Version::VERSION, '3.8.0-b4', '>')) {
            $apiToTest[] = array(
                'API.get',
                array(
                    'idSite'  => self::$fixture->idSite,
                    'date'    => $dateWithPluginEnabled,
                    'periods' => array('day'),
                )
            );
        }

        $api         = array(
            'MarketingCampaignsReporting'
        );

        $columnsToHide = '';
        if (version_compare(Version::VERSION, '3.8.0-b4', '<')) {
            $columnsToHide = [
                'MarketingCampaignsReporting_CampaignName',
                'MarketingCampaignsReporting_CampaignContent',
                'MarketingCampaignsReporting_CampaignMedium',
                'MarketingCampaignsReporting_CampaignKeyword',
                'MarketingCampaignsReporting_CombinedKeywordContent',
                'MarketingCampaignsReporting_CampaignSource',
                'MarketingCampaignsReporting_CampaignSourceMedium',
                'segment'
            ];
        }

        $apiToTest[] = array(
            $api,
            array(
                'idSite'                 => self::$fixture->idSite,
                'date'                   => $dateWithPluginEnabled,
                'periods'                => array('day'),
                'testSuffix'             => 'expanded',
                'otherRequestParameters' => array('expanded' => 1)
            )
        );
        $apiToTest[] = array(
            $api,
            array(
                'idSite'                 => self::$fixture->idSite,
                'date'                   => $dateWithPluginEnabled,
                'periods'                => array('day'),
                'testSuffix'             => 'flat',
                'otherRequestParameters' => array('flat' => 1, 'expanded' => 0),
                'xmlFieldsToRemove'      => $columnsToHide
            )
        );
        $apiToTest[] = array(
            $api,
            array(
                'idSite'                 => self::$fixture->idSite,
                'date'                   => $dateWithPluginEnabled,
                'periods'                => array('day'),
                'testSuffix'             => 'segmentedMatchAll',
                'segment'                => 'campaignName!=test;campaignKeyword!=test;campaignSource!=test;campaignMedium!=test;campaignContent!=test;campaignId!=test',
                'otherRequestParameters' => array('flat' => 1, 'expanded' => 0),
                'xmlFieldsToRemove'      => $columnsToHide
            )
        );
        $apiToTest[] = array(
            $api,
            array(
                'idSite'                 => self::$fixture->idSite,
                'date'                   => $dateWithPluginEnabled,
                'periods'                => array('day'),
                'testSuffix'             => 'segmentedMatchNone',
                'segment'                => 'campaignName==test,campaignKeyword==test,campaignSource==test,campaignMedium==test,campaignContent==test,campaignId==test',
                'otherRequestParameters' => array('flat' => 1, 'expanded' => 0),
                'xmlFieldsToRemove'      => $columnsToHide
            )
        );

        $apiToTest[] = array(
            'MarketingCampaignsReporting',
            array(
                'idSite'       => 'all',
                'date'         => $dateTime,
                'periods'      => 'day',
                'setDateLastN' => true,
                'testSuffix'   => 'multipleDatesSites_',
            )
        );

        // row evolution tests for methods that also use Referrers plugin data
        $apiToTest[] = array(
            'API.getRowEvolution',
            array(
                'idSite'                 => self::$fixture->idSite,
                'date'                   => $dateTime,
                'testSuffix'             => 'getName',
                'otherRequestParameters' => array(
                    'date'      => '2013-01-20,2013-01-25',
                    'period'    => 'day',
                    'apiModule' => 'MarketingCampaignsReporting',
                    'apiAction' => 'getName',
                    'label'     => 'campaign_hashed',
                    'expanded'  => 0
                )
            )
        );

        $apiToTest[] = array(
            'API.getRowEvolution',
            array(
                'idSite'                 => self::$fixture->idSite,
                'date'                   => $dateTime,
                'testSuffix'             => 'getKeyword',
                'otherRequestParameters' => array(
                    'date'      => '2013-01-20,2013-01-25',
                    'period'    => 'day',
                    'apiModule' => 'MarketingCampaignsReporting',
                    'apiAction' => 'getKeyword',
                    'label'     => 'mot_clé_pépère',
                    'expanded'  => 0
                )
            )
        );

        return $apiToTest;
    }

    public function getReferrerApiForTesting()
    {
        $dateWithPluginEnabled = self::$fixture->dateTimeWithPluginEnabled;
        $apiToTest             = array();

        $api         = array(
            'Referrers.getCampaigns',
        );

        $columnsToHide = [];
        if (version_compare(Version::VERSION, '3.8.0-b4', '<')) {
            $columnsToHide = ['Referrers_Campaign', 'Referrers_Keyword'];
        }

        $apiToTest[] = array(
            $api,
            array(
                'idSite'                 => self::$fixture->idSite,
                'date'                   => $dateWithPluginEnabled,
                'periods'                => array('day'),
                'testSuffix'             => 'expanded',
                'otherRequestParameters' => array('expanded' => 1)
            )
        );
        $apiToTest[] = array(
            $api,
            array(
                'idSite'                 => self::$fixture->idSite,
                'date'                   => $dateWithPluginEnabled,
                'periods'                => array('day'),
                'testSuffix'             => 'flat',
                'otherRequestParameters' => array('flat' => 1, 'expanded' => 0),
                'xmlFieldsToRemove'      => $columnsToHide
            )
        );

        return $apiToTest;
    }

}

TrackSeveralCampaignsTest::$fixture = new TrackAdvancedCampaigns();
