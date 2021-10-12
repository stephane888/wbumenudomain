<?php
namespace Drupal\wbumenudomain\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;

/**
 * Provides a list controller for wbumenudomain entity.
 *
 * @ingroup wbumenudomain
 */
class WbumenudomainListBuilder extends EntityListBuilder
{

    /**
     *
     * {@inheritdoc} We override ::render() so that we can add our own content above the table.
     *               parent::render() is where EntityListBuilder creates the table using our
     *               buildHeader() and buildRow() implementations.
     */
    public function render()
    {
        $build['description'] = [
            '#markup' => $this->t('<p>Wbumenudomain Entity implements a wbumenudomain model.<br>  These wbumenudomains are fieldable entities. You can manage the fields on the <a href="@adminlink">Wbumenudomain admin page</a>.</p>', array(
                '@adminlink' => \Drupal::urlGenerator()->generateFromRoute('wbumenudomain.wbumenudomain_settings')
            ))
        ];

        $build += parent::render();
        return $build;
    }

    /**
     *
     * {@inheritdoc} Building the header and content lines for the contact list.
     *              
     *               Calling the parent::buildHeader() adds a column for the possible actions
     *               and inserts the 'edit' and 'delete' links as defined for the entity type.
     */
    public function buildHeader()
    {
        $header['id'] = $this->t('WbumenudomainID');
        $header['hostname'] = $this->t('Name');
        // $header['first_name'] = $this->t('First Name');
        // $header['gender'] = $this->t('Gender');
        return $header + parent::buildHeader();
    }

    /**
     *
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        /* @var $entity \Drupal\wbumenudomain\Entity\Wbumenudomain */
        $row['id'] = $entity->id();
        $row['hostname'] = $entity->toLink();
        // $row['first_name'] = $entity->first_name->value;
        // $row['gender'] = $entity->gender->value;
        return $row + parent::buildRow($entity);
    }
}