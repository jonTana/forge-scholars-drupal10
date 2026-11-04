<?php

declare(strict_types=1);

namespace Drupal\forge_scholars_signup\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Collects an application-kit request.
 */
final class KitRequestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'forge_scholars_kit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#attributes']['class'][] = 'fs-form';

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#required' => TRUE,
      '#maxlength' => 80,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['track'] = [
      '#type' => 'select',
      '#title' => $this->t('Track'),
      '#options' => [
        'unsure' => $this->t('Not sure yet'),
        'civic' => $this->t('Civic build'),
        'climate' => $this->t('Climate craft'),
        'teaching' => $this->t('Teaching lab'),
        'main-street' => $this->t('Main street'),
      ],
      '#default_value' => 'unsure',
    ];
    $form['company'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Company'),
      '#attributes' => [
        'tabindex' => '-1',
        'autocomplete' => 'off',
        'aria-hidden' => 'true',
      ],
      '#wrapper_attributes' => [
        'class' => ['fs-hp'],
      ],
    ];
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send me the kit'),
      '#attributes' => ['class' => ['fs-submit']],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $name = trim((string) $form_state->getValue('first_name'));
    if ($name === '') {
      $form_state->setErrorByName('first_name', $this->t('Add your first name.'));
    }
    $email = strtolower(trim((string) $form_state->getValue('email')));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Use a real email so the kit can arrive.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $company = trim((string) $form_state->getValue('company'));
    if ($company === '') {
      $allowed = ['unsure', 'civic', 'climate', 'teaching', 'main-street'];
      $track = (string) $form_state->getValue('track');
      if (!in_array($track, $allowed, TRUE)) {
        $track = 'unsure';
      }
      \Drupal::database()->insert('forge_scholars_signup')->fields([
        'first_name' => trim((string) $form_state->getValue('first_name')),
        'email' => strtolower(trim((string) $form_state->getValue('email'))),
        'track' => $track,
        'created' => \Drupal::time()->getRequestTime(),
      ])->execute();
    }
    $form_state->setRedirect('forge_scholars.front', [], ['query' => ['joined' => '1']]);
  }

}
