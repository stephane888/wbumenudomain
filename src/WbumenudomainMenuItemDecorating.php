<?php
namespace Drupal\wbumenudomain;

use Drupal\Core\Menu\DefaultMenuLinkTreeManipulators;
use Drupal\Core\Access\AccessManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\wbumenudomain\Entity\Wbumenudomain;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\Core\Access\AccessResult;

class WbumenudomainMenuItemDecorating extends DefaultMenuLinkTreeManipulators
{

    private $CurrentHostName;

    private $Wbumenudomain = [];

    /**
     *
     * @param AccessManagerInterface $access_manager
     * @param AccountInterface $account
     * @param EntityTypeManagerInterface $entity_type_manager
     */
    public function __construct(AccessManagerInterface $access_manager, AccountInterface $account, EntityTypeManagerInterface $entity_type_manager)
    {
        parent::__construct($access_manager, $account, $entity_type_manager);
        $this->CurrentHostName = $this->convertStringTomachineName($_SERVER['HTTP_HOST']);
        $this->getCurrentWbumenudomain();
    }

    /**
     * Checks access for one menu link instance.
     *
     * @param \Drupal\Core\Menu\MenuLinkInterface $instance
     *            The menu link instance.
     *            
     * @return \Drupal\Core\Access\AccessResultInterface The access result.
     */
    protected function menuLinkCheckAccess(MenuLinkInterface $link)
    {
        $access_result = parent::menuLinkCheckAccess($link);
        $metadata = $link->getMetaData();
        $menuName = $link->getMenuName();
        if ($menuName == 'main' && ! empty($metadata['entity_id'])) {
            // $this->entityTypeManager->getStorage('menu_link_content')->load($metadata['entity_id']);
            if (! in_array($metadata['entity_id'], $this->Wbumenudomain)) {
                $access_result = AccessResult::forbidden();
            }
        }
        return $access_result->cachePerPermissions();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Drupal\Core\Menu\DefaultMenuLinkTreeManipulators::checkAccess()
     */
    public function checkAccess($tree)
    {
        $tree = parent::checkAccess($tree);
        /**
         * --
         */
        foreach ($tree as $element) {
            /**
             *
             * @var \Drupal\Core\Menu\MenuLinkDefault $link
             */
            $link = $element->link;
            if ($link->getMenuName() == 'main') {
                // dump($link->getOptions());
            }
        }
        return $tree;
    }

    /**
     * --
     */
    private function getCurrentWbumenudomain()
    {
        $query = \Drupal::entityQuery('wbumenudomain');
        $query->condition('hostname', $this->CurrentHostName);
        $ids = $query->execute();

        $Wbumenudomain = Wbumenudomain::loadMultiple($ids);
        if (! empty($Wbumenudomain)) {
            /**
             *
             * @var \Drupal\wbumenudomain\Entity\Wbumenudomain $Wbumenudomain
             */
            $Wbumenudomain = reset($Wbumenudomain);
            $this->Wbumenudomain = $Wbumenudomain->get('field_element_de_menu_valides')->getValue();
            if (! empty($this->Wbumenudomain)) {
                $this->Wbumenudomain = reset($this->Wbumenudomain);
                $this->Wbumenudomain = Json::decode($this->Wbumenudomain['value']);
            }
        }
    }

    private function convertStringTomachineName($new_value)
    {
        $new_value = strtolower($new_value);
        $new_value = preg_replace('/[^a-z0-9_]+/', '_', $new_value);
        return preg_replace('/_+/', '_', $new_value);
    }
}