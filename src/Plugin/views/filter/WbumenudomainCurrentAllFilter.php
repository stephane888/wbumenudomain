<?php
namespace Drupal\wbumenudomain\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\BooleanOperator;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\domain_access\DomainAccessManagerInterface;

/**
 * Handles matching of current domain.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("wbumenudomain_current_all_filter")
 */
class WbumenudomainCurrentAllFilter extends BooleanOperator
{

    /**
     * Definit le label;
     *
     * {@inheritdoc}
     *
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL)
    {
        parent::init($view, $display, $options);
        $this->value_value = t('Available on current domain (2)');
    }

    /**
     *
     * {@inheritdoc}
     */
    protected function operators()
    {
        return [];
    }

    /**
     *
     * {@inheritdoc}
     */
    public function query()
    {
        $this->ensureMyTable();
        $all_table = $this->query->addTable('node__field_domain_all_affiliates', $this->relationship);
        $all_field = $all_table . '.field_domain_all_affiliates_value';
        $real_field = $this->tableAlias . '.' . $this->realField;
        /** @var \Drupal\domain\DomainNegotiatorInterface $domain_negotiator */
        $domain_negotiator = \Drupal::service('domain.negotiator');
        $current_domain = $domain_negotiator->getActiveDomain();
        $current_domain_id = $current_domain->id();
        // dump($current_domain_id);
        // dump($this->value);
        // dump($this->realField);
        // if (empty($this->value)) {
        // $where = "(($real_field <> '$current_domain_id' OR $real_field IS NULL) AND ($all_field = 0 OR $all_field IS NULL))";
        // if ($current_domain->isDefault()) {
        // $where = "($real_field <> '$current_domain_id' AND ($all_field = 0 OR $all_field IS NULL))";
        // }
        // } else {
        // $where = "($real_field = '$current_domain_id' OR $all_field = 1)";
        // if ($current_domain->isDefault()) {
        // $where = "(($real_field = '$current_domain_id' OR $real_field IS NULL) OR $all_field = 1)";
        // }
        // }
        // $this->query->addWhereExpression($this->options['group'], $where);
        // // This filter causes duplicates.
        // $this->query->options['distinct'] = TRUE;

        if (! empty($this->value)) {
            $this->query->addWhere('AND', $real_field, $current_domain_id, '=');
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function getCacheContexts()
    {
        $contexts = parent::getCacheContexts();

        $contexts[] = 'url.site';

        return $contexts;
    }
}