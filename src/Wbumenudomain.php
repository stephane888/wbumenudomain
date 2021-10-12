<?php
namespace Drupal\wbumenudomain;

use Drupal\domain\Entity\Domain;
use Drupal\wbumenudomain\Entity\Wbumenudomain as WbumenudomainEntity;

class Wbumenudomain
{

    /**
     * -
     */
    public static function getLibrairiesCurrentTheme()
    {
        /**
         *
         * @var \Drupal\Core\Theme\ThemeInitialization $themeInitialization
         */
        $theme_name = \Drupal::config('system.theme')->get('default');
        $themeInitialization = \Drupal::service("theme.initialization");
        /* @var \Drupal\Core\Theme\ActiveTheme $theme */
        $theme = $themeInitialization->initTheme($theme_name);
        return [
            'lesroisdelareno/prestataires_m0' => 'lesroisdelareno/prestataires_m0',
            'lesroisdelareno/prestataires_m1' => 'lesroisdelareno/prestataires_m1',
            'lesroisdelareno/prestataires_m2' => 'lesroisdelareno/prestataires_m2',
            'lesroisdelareno/prestataires_m3' => 'lesroisdelareno/prestataires_m3',
            'lesroisdelareno/prestataires_m4' => 'lesroisdelareno/prestataires_m4'
        ];
    }

    /**
     * --
     */
    public static function getAlldomaines()
    {
        // $StorageDomain = \Drupal::entityTypeManager()->getStorage("domain");
        $query = \Drupal::entityQuery('domain');
        $domainIds = $query->execute();
        $domains = Domain::loadMultiple($domainIds);
        $hostnames = [];
        foreach ($domains as $domain) {
            $hostnames[$domain->id()] = $domain->get('name');
        }
        return $hostnames;
    }

    /**
     * --
     */
    public static function getUnUseDomain($value = null)
    {
        $domaines = self::getAlldomaines();
        $UseDomain = self::getEntityWbumenudomain();
        $UnUseDomaines = [];
        foreach ($domaines as $k => $domaine) {
            if ($value == $k || ! isset($UseDomain[$k])) {
                $UnUseDomaines[$k] = $domaine;
            }
        }
        return $UnUseDomaines;
    }

    /**
     * --
     */
    public static function getEntityWbumenudomain()
    {
        $query = \Drupal::entityQuery('wbumenudomain');
        $domainIds = $query->execute();
        $wbumenudomains = WbumenudomainEntity::loadMultiple($domainIds);
        $hostnames = [];
        foreach ($wbumenudomains as $wbumenudomain) {
            $hostnames[$wbumenudomain->getHostname()] = $wbumenudomain->getHostname();
        }
        return $hostnames;
    }
}