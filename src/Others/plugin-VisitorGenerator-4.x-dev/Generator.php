<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\VisitorGenerator;

use Piwik\SettingsPiwik;

include_once __DIR__ . '/vendor/autoload.php';

class Generator
{
    protected $faker;
    protected $matomoUrl;

    /**
     * @param null $matomoUrl
     */
    public function __construct($matomoUrl = null)
    {
        $this->faker = \Faker\Factory::create('en_EN');
        $this->faker->addProvider(new Faker\Request($this->faker));
        $this->setMatomoUrl($matomoUrl);
    }

    /**
     * @param $matomoUrl
     */
    protected function setMatomoUrl($matomoUrl) {
        $this->matomoUrl = $matomoUrl;
    }

    /**
     * @return string
     */
    protected function getMatomoUrl()
    {
        if($this->matomoUrl) {
            $url = $this->matomoUrl;
        } else {
            $url = SettingsPiwik::getPiwikUrl();
        }

        // this is a workaround when force_ssl=1, and the HTTPS URL is not fetchable from CLI
        $url = str_replace('https://localhost', 'http://localhost', $url);
        return $url;
    }

}
