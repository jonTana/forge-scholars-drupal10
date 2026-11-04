<?php

declare(strict_types=1);

namespace Drupal\forge_scholars_signup\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Lists kit requests for staff.
 */
final class SignupAdminController extends ControllerBase {

  /**
   * Renders the latest kit requests.
   */
  public function listing(): array {
    $records = \Drupal::database()->select('forge_scholars_signup', 's')
      ->fields('s')
      ->orderBy('created', 'DESC')
      ->range(0, 200)
      ->execute()
      ->fetchAll();

    $rows = [];
    $formatter = \Drupal::service('date.formatter');
    foreach ($records as $record) {
      $rows[] = [
        $formatter->format((int) $record->created, 'short'),
        $record->first_name,
        $record->email,
        $record->track,
      ];
    }

    return [
      '#type' => 'table',
      '#header' => [
        $this->t('When'),
        $this->t('Name'),
        $this->t('Email'),
        $this->t('Track'),
      ],
      '#rows' => $rows,
      '#empty' => $this->t('No kit requests yet.'),
      '#cache' => ['max-age' => 0],
    ];
  }

}
