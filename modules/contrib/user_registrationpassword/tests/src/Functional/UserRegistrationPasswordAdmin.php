<?php

namespace Drupal\Tests\user_registrationpassword\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Functionality tests for User registration password module.
 *
 * @group user_registrationpassword
 */
class UserRegistrationPasswordAdmin extends BrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['user_registrationpassword'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    // User to add and remove language.
    $this->adminUser = $this->drupalCreateUser(['administer account settings', 'administer users']);
    // User to check non-admin access.
    $this->regularUser = $this->drupalCreateUser();
  }

  /**
   * Implements testRegistrationWithEmailVerificationAndPasswordAdmin().
   */
  public function testRegistrationWithEmailVerificationAndPasswordAdmin() {
    // Login with admin user.
    $this->drupalLogin($this->adminUser);

    // Test the default options.
    $this->drupalGet('admin/config/people/accounts');
    $edit_first = ['user_register' => USER_REGISTER_VISITORS, 'user_registrationpassword_registration' => USER_REGISTRATIONPASSWORD_VERIFICATION_PASS];
    $this->drupalPostForm('admin/config/people/accounts', $edit_first, t('Save configuration'));

    // Load config.
    $user_config = \Drupal::configFactory()->getEditable('user.settings');
    // Variable verify_mail.
    $this->assertEqual($user_config->get('verify_mail', TRUE), FALSE, 'Variable verify_mail set correctly.');
    // Variable notify.register_pending_approval.
    $this->assertEqual($user_config->get('notify.register_pending_approval', TRUE), FALSE, 'Variable notify.register_pending_approval set correctly.');
    // Variable notify.register_no_approval_required.
    $this->assertEqual($user_config->get('notify.register_no_approval_required', TRUE), FALSE, 'Variable notify.register_no_approval_required set correctly.');

    // Test the admin approval option.
    $this->drupalGet('admin/config/people/accounts');
    $edit_second = [
      'user_register' => USER_REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL,
      'user_registrationpassword_registration' => USER_REGISTRATIONPASSWORD_VERIFICATION_PASS,
    ];
    $this->drupalPostForm('admin/config/people/accounts', $edit_second, t('Save configuration'));

    // Load config.
    $user_config = \Drupal::configFactory()->getEditable('user.settings');
    // Variable verify_mail.
    $this->assertEqual($user_config->get('verify_mail', FALSE), TRUE, 'Variable verify_mail set correctly.');
    // Variable notify.register_pending_approval.
    $this->assertEqual($user_config->get('notify.register_pending_approval', FALSE), TRUE, 'Variable notify.register_pending_approval set correctly.');
    // Variable notify.register_no_approval_required.
    $this->assertEqual($user_config->get('notify.register_no_approval_required', FALSE), TRUE, 'Variable notify.register_no_approval_required set correctly.');

    // Test the admin only option.
    $this->drupalGet('admin/config/people/accounts');
    $edit_third = [
      'user_register' => USER_REGISTER_ADMINISTRATORS_ONLY,
      'user_registrationpassword_registration' => USER_REGISTRATIONPASSWORD_VERIFICATION_PASS,
    ];
    $this->drupalPostForm('admin/config/people/accounts', $edit_third, t('Save configuration'));

    // Load config.
    $user_config = \Drupal::configFactory()->getEditable('user.settings');
    // Variable verify_mail.
    $this->assertEqual($user_config->get('verify_mail', FALSE), TRUE, 'Variable verify_mail set correctly.');
    // Variable notify.register_pending_approval.
    $this->assertEqual($user_config->get('notify.register_pending_approval', TRUE), FALSE, 'Variable notify.register_pending_approval set correctly.');
    // Variable notify.register_no_approval_required.
    $this->assertEqual($user_config->get('notify.register_no_approval_required', TRUE), FALSE, 'Variable notify.register_no_approval_required set correctly.');
  }

}
