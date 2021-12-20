<?php
namespace Drupal\wbumenudomain\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\WidgetBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\wbumenudomain\Services\WbumenudomainSiteconfig as WbumenudomainConf;
use Stephane888\HtmlBootstrap\ThemeUtility;

/**
 * A widget bar.
 *
 * @FieldWidget(
 *   id = "wbumenudomainsiteconfig",
 *   label = @Translation(" Wbumenudomain Widget Site Config "),
 *   field_types = {
 *     "wbumenudomaineditlink",
 *   }
 * )
 */
class WbumenudomainSiteconfig extends WidgetBase
{

    protected $WbumenudomainConf;

    protected $ThemeUtility;

    /**
     * Constructs a WidgetBase object.
     *
     * @param string $plugin_id
     *            The plugin_id for the widget.
     * @param mixed $plugin_definition
     *            The plugin implementation definition.
     * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
     *            The definition of the field to which the widget is associated.
     * @param array $settings
     *            The widget settings.
     * @param array $third_party_settings
     *            Any third party settings.
     */
    public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, WbumenudomainConf $WbumenudomainConf, ThemeUtility $ThemeUtility)
    {
        parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
        $this->WbumenudomainConf = $WbumenudomainConf;
        $this->ThemeUtility = $ThemeUtility;
    }

    /**
     *
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static($plugin_id, $plugin_definition, $configuration['field_definition'], $configuration['settings'], $configuration['third_party_settings'], $container->get('wbumenudomain.site_config'), $container->get('wbumenudomain.themeutility'));
    }

    /**
     *
     * {@inheritdoc}
     */
    public static function defaultSettings()
    {
        return [
            'placeholder' => ''
        ] + parent::defaultSettings();
    }

    /**
     *
     * {@inheritdoc}
     */
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state)
    {
        
        $hostname = $form_state->getValue('hostname');
        if (! empty($hostname[0]['value'])) {
            $hostname = $hostname[0]['value'];
            //
            $this->ThemeUtility->ActiveUseAjax();
            // $form_state->setRebuild();
            
        }
        if (! $hostname) {
            /**
             *
             * @var \Drupal\wbumenudomain\Entity\Wbumenudomain $wbumenudomain
             */
            if ($wbumenudomain = \Drupal::routeMatch()->getParameter('wbumenudomain')) {
                $hostname = $wbumenudomain->getHostname();
            }
        }
        
        $element['siteconf'] = [];
        $this->ThemeUtility->addContainerTree('container', $element['siteconf'], 'Configuration du site', true);
        $element['siteconf']['container']['#prefix'] = '<div id="wbumenudomain-siteconfig">';
        $element['siteconf']['container']['#suffix'] = '</div>';
        $element['siteconf']['container']['#element_validate'][] = [
            $this,
            'validateElement'
        ];
        // $element['siteconf']['container']['#value_callback'][] = [
        // static::class,
        // 'ConfigSaveSubmit__'
        // ];

        if (! empty($hostname)) {
            $siteConf = $this->WbumenudomainConf->getValue('domain.config.' . $hostname . '.system.site');
            
            // die();
            $this->formSiteConfig($element['siteconf']['container'], $siteConf, 'domain.config.' . $hostname . '.system.site');
        }
        
        // die();
        // $form_state->setRebuild();
        return $element;
    }

    public function formSiteConfig(array &$form, array $siteConf = [], $id_config)
    {
        // edit config
        $this->ThemeUtility->addHiddenTree('edit-config', $form, $id_config);
        // name
        $name = isset($siteConf['name']) ? $siteConf['name'] : '';
        $this->ThemeUtility->addTextfieldTree('name', $form, 'Nom du site', $name);
        // slogan
        $slogan = isset($siteConf['slogan']) ? $siteConf['slogan'] : '';
        $this->ThemeUtility->addTextfieldTree('slogan', $form, 'Slogan', $slogan);
        // mail
        $mail = isset($siteConf['mail']) ? $siteConf['mail'] : '';
        $this->ThemeUtility->addTextfieldTree('mail', $form, 'Adresse de courriel', $mail);
        // page.front
        $page_front = isset($siteConf['page']["front"]) ? $siteConf['page']["front"] : '';
        $this->ThemeUtility->addTextfieldTree('page.front', $form, "Page d'accueil par défaut", $page_front);
        // page.403
        $page_403 = isset($siteConf['page']["403"]) ? $siteConf['page']["403"] : '';
        $this->ThemeUtility->addTextfieldTree('page.403', $form, "Page 403 par défaut (accès refusé)", $page_403);
        // page.404
        $page_404 = isset($siteConf["page"]["404"]) ? $siteConf["page"]["404"] : '';
        $this->ThemeUtility->addTextfieldTree('page.404', $form, "Page 404 par défaut ( page non trouvée )", $page_404);
    }

    public function validateElement(array $element, FormStateInterface $form_state)
    {
       
        // die();
        $vals = $form_state->getUserInput();
        // name
        if (! empty($vals['field_site_information'][0]['siteconf']['container'])) {
            $siteConfValue = $vals['field_site_information'][0]['siteconf']['container'];
            // idConf
            $this->WbumenudomainConf->SetIdConfig($siteConfValue['edit-config']);
            // name
            $this->WbumenudomainConf->SaveValue('name', $siteConfValue['name']);
            // slogan
            $this->WbumenudomainConf->SaveValue('slogan', $siteConfValue['slogan']);
            // mail
            $this->WbumenudomainConf->SaveValue('mail', $siteConfValue['mail']);
            // page.front
            $this->WbumenudomainConf->SaveValue('page.front', $siteConfValue['page.front']);
            // page.403
            $this->WbumenudomainConf->SaveValue('page.403', $siteConfValue['page.403']);
            // page.404
            $this->WbumenudomainConf->SaveValue('page.404', $siteConfValue['page.404']);
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state)
    {
        $element['placeholder'] = [
            '#type' => 'textfield',
            '#title' => t('Placeholder'),
            '#default_value' => $this->getSetting('placeholder'),
            '#description' => t(' Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format. ')
        ];
        return $element;
    }
}