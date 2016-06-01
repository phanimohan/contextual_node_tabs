<?php

/**
 * @file
 * Contains \Drupal\contextual_node_tabs\Plugin\Block\ContextualNodeTabsBlock.
 */

namespace Drupal\contextual_node_tabs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Menu\LocalTaskManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Contextual Node Tabs: configurable node local tasks' block.
 *
 * Drupal\Core\Block\BlockBase gives us a very useful set of basic functionality
 * for this configurable block. We can just fill in a few of the blanks with
 * defaultConfiguration(), blockForm(), blockSubmit(), and build().
 *
 * @Block(
 *   id = "contextual_node_tabs",
 *   admin_label = @Translation("Contextual node tabs")
 * )
 */
class ContextualNodeTabsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The local task manager.
   *
   * @var \Drupal\Core\Menu\LocalTaskManagerInterface
   */
  protected $localTaskManager;

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Creates a ContextualNodeTabsBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Menu\LocalTaskManagerInterface $local_task_manager
   *   The local task manager.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LocalTaskManagerInterface $local_task_manager, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->localTaskManager = $local_task_manager;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.menu.local_task'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'label_display' => FALSE,
      'auto_hide' => FALSE,
			'align' => TRUE,
			'position' => 'fixed',
			'icon' => 'settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configuration;
		$cacheability = new CacheableMetadata();
    $contextual_tabs = [
      '#theme' => 'contextual_local_tasks',
			'#config' => $config,
    ];

    // Add only selected levels for the printed output.
    $links = $this->localTaskManager->getLocalTasks($this->routeMatch->getRouteName(), 0);
		$cacheability = $cacheability->merge($links['cacheability']);
		$contextual_tabs += [
			'#primary' => count(Element::getVisibleChildren($links['tabs'])) > 0 ? $links['tabs'] : [],
		];
		
    $links = $this->localTaskManager->getLocalTasks($this->routeMatch->getRouteName(), 1);
		$cacheability = $cacheability->merge($links['cacheability']);
		$contextual_tabs += [
			'#secondary' => count(Element::getVisibleChildren($links['tabs'])) > 0 ? $links['tabs'] : [],
		];

    $build = [];
    $cacheability->applyTo($build);
    if (empty($contextual_tabs['#primary']) && empty($contextual_tabs['#secondary'])) {
      return $build;
    }
		
    return $build + $contextual_tabs;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->configuration;
    $defaults = $this->defaultConfiguration();

		$form['alignment'] = [
      '#type' => 'details',
      '#title' => $this->t('Aditional Settings'),
      '#description' => $this->t('Additional settings for contextual node tabs icon'),
      // Open if not set to defaults.
      '#open' => $defaults['auto_hide'] !== $config['auto_hide'] || $defaults['align'] !== $config['align'] || $defaults['position'] !== $config['position'],
    ];
    $form['alignment']['auto_hide'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto hide the icon'),
      '#default_value' => $config['auto_hide'],
    ];
    $form['alignment']['align'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Right Alignment'),
      '#default_value' => $config['align'],
    ];
		$form['alignment']['position'] = [
      '#type' => 'select',
      '#title' => $this->t('Icon position'),
			'#options' => array(
        'fixed' => t('Fixed'),
        'floating' => t('Floating'),
      ),
      '#default_value' => $config['position'],
    ];
		$form['alignment']['icon'] = [
      '#type' => 'select',
      '#title' => $this->t('Icon style'),
			'#options' => array(
        'settings' => t('Gear Icon'),
				'orange_settings' => t('Orange Gear Icon'),
        'vertical_dots' => t('Vertical Dots'),
				'horizontal_dots' => t('Horizontal Dots'),
				'menu' => t('Menu Icon'),
				'down_arrow' => t('Down Arrow Icon'),
      ),
      '#default_value' => $config['icon'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $alignments = $form_state->getValue('alignment');
    $this->configuration['auto_hide'] = $alignments['auto_hide'];
    $this->configuration['align'] = $alignments['align'];
		$this->configuration['position'] = $alignments['position'];
		$this->configuration['icon'] = $alignments['icon'];
  }
	
	/**
   * {@inheritdoc}
   */
	public function getContextualNodeTasks() {
		
	}

}
