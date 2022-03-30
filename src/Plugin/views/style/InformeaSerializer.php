<?php

namespace Drupal\drupal_informea_api\Plugin\views\style;

use Drupal\rest\Plugin\views\style\Serializer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The style plugin for serialized output formats.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "informea_serializer",
 *   title = @Translation("InforMEA Serializer"),
 *   help = @Translation("Serializes views row data and pager using the Serializer component
 * and adds specific customizations for InforMEA."),
 *   display_types = {"data"}
 * )
 */
class InformeaSerializer extends Serializer {

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('serializer'),
      $container->getParameter('serializer.formats'),
      $container->getParameter('serializer.format_providers'),
      $container->get('request_stack')
    );
  }

  /**
   * Constructs a Plugin object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SerializerInterface $serializer, array $serializer_formats, array $serializer_format_providers, RequestStack $request_stack) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer, $serializer_formats, $serializer_format_providers);

    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    if ($this->requestStack->getCurrentRequest()->query->get('$count') != 'true') {
      return parent::render();
    }

    // If the query countains $count=true, return the result count instead.
    $pager = $this->view->pager;
    $total_items = $pager->getTotalItems();

    // Get the content type configured in the display or fallback to the
    // default.
    if ((empty($this->view->live_preview))) {
      $content_type = $this->displayHandler->getContentType();
    }
    else {
      $content_type = !empty($this->options['formats']) ? reset($this->options['formats']) : 'json';
    }

    return $this->serializer->serialize(['count' => $total_items], $content_type, ['views_style_plugin' => $this]);
  }

}