<?php

declare(strict_types=1);

namespace Drupal\forge_scholars_signup\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\forge_scholars_signup\Form\KitRequestForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * Renders the scholarship landing page.
 */
final class LandingController extends ControllerBase {

  /**
   * Builds the front page.
   */
  public function page(Request $request): array {
    return [
      '#theme' => 'forge_landing',
      '#form' => $this->formBuilder()->getForm(KitRequestForm::class),
      '#joined' => $request->query->get('joined') === '1',
      '#cache' => [
        'contexts' => ['url.query_args:joined'],
      ],
    ];
  }

}
