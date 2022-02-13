<?php

namespace Drupal\wbumenudomain;

use Drupal\domain\Entity\Domain;
use Drupal\wbumenudomain\Entity\Wbumenudomain as WbumenudomainEntity;

class Wbumenudomain {
  
  /**
   * -
   */
  public static function getLibrairiesCurrentTheme() {
    return [
      'lesroisdelareno/prestataires_m0' => 'Theme les rois de la reno',
      'lesroisdelareno/prestataires_m1' => 'Theme Gabi',
      'lesroisdelareno/prestataires_m2' => 'Theme Bigger',
      'lesroisdelareno/prestataires_m3' => 'Theme Extra',
      'lesroisdelareno/prestataires_m4' => 'Theme Farade',
      'lesroisdelareno/prestataires_m6' => 'Theme Commerce',
      'lesroisdelareno/prestataires_m5' => 'Theme partenaire',
      'lesroisdelareno/prestataires_m7' => 'Theme architecte',
      'lesroisdelareno/prestataires_m8' => 'Theme rc-web'
    ];
  }
  
  /**
   * --
   */
  public static function getContentTypeHomePage(string $key) {
    $types = [
      'lesroisdelareno/prestataires_m0' => 'model_page_d_accueil',
      'lesroisdelareno/prestataires_m1' => 'model_d_affichage_theme_gabi_',
      'lesroisdelareno/prestataires_m2' => 'model_d_affichage_theme_bigger_',
      'lesroisdelareno/prestataires_m3' => 'theme_extra',
      'lesroisdelareno/prestataires_m4' => 'model_d_affichage_theme_farade_',
      'lesroisdelareno/prestataires_m6' => 'model_d_affichage_theme_commerce',
      'lesroisdelareno/prestataires_m5' => 'model_d_affichage_theme_partenai',
      'lesroisdelareno/prestataires_m7' => 'model_d_affichage_architecte_',
      'lesroisdelareno/prestataires_m8' => 'model_d_affichage_rc_webr_'
    ];
    if (!empty($types[$key])) {
      return $types[$key];
    }
  }
  
  /**
   * --
   */
  public static function getAlldomaines() {
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
   * - Recupere la liste des domaines non utilisé.
   */
  public static function getUnUseDomain($value = null, $entityTypeId = null) {
    $domaines = self::getAlldomaines();
    $UseDomain = self::getEntityWbumenudomain($entityTypeId);
    // dump($UseDomain);
    // dump($domaines);
    $UnUseDomaines = [];
    foreach ($domaines as $k => $domaine) {
      if ($value == $k || !isset($UseDomain[$k])) {
        $UnUseDomaines[$k] = $domaine;
      }
    }
    return $UnUseDomaines;
  }
  
  /**
   * --
   */
  public static function getEntityWbumenudomain($entityTypeId = null) {
    if ($entityTypeId == 'node') {
      return [];
    }
    else {
      $query = \Drupal::entityQuery($entityTypeId);
      $domainIds = $query->execute();
      $entityType = \Drupal::entityTypeManager()->getStorage($entityTypeId);
      $hostnames = [];
      if ($entityType) {
        $wbumenudomains = $entityType->loadMultiple($domainIds);
        foreach ($wbumenudomains as $wbumenudomain) {
          $hostnames[$wbumenudomain->getHostname()] = $wbumenudomain->getHostname();
        }
      }
      return $hostnames;
    }
  }
  
  /**
   * Cette function doit etre sur un autre module externe à ce dernier.
   *
   * @deprecated
   * @return string|number|NULL
   */
  public static function getCurrentdomain() {
    /** @var \Drupal\domain\Entity\Domain $active */
    $active = \Drupal::service('domain.negotiator')->getActiveDomain();
    if (empty($active)) {
      $active = \Drupal::entityTypeManager()->getStorage('domain')->loadDefaultDomain();
    }
    if (!empty($active))
      return $active->id();
  }
  
}