<?php

namespace Drupal\evolent_contact\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * This is my simple add form for managing contact details usiong Drupal Form API.
 */
class EvolentContactAddForm extends FormBase
{
    /**
     * Here we return formId to drupal core for understading.
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'evolent_contact_add_form';
    }

    /**
     * This actaully building form that are shown on front end.
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First name'),
            '#required' => true,
            '#maxlength' => 20,
        ];

        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last name'),
            '#required' => true,
            '#maxlength' => 20,
        ];
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#required' => true,
            '#maxlength' => 100,
        ];
        $form['phone_no'] = [
            '#type' => 'tel',
            '#title' => $this->t('Phone no'),
            '#required' => true,
            '#maxlength' => 11,
        ];
        $form['status'] = [
            '#type' => 'radios',
            '#title' => t('Contact Status'),
            '#default_value' => 'Active',
            '#options' => array('Active' => 'Active', 'Deactive' => 'Deactive'),
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#button_type' => 'primary',
        ];

        $form['cancel'] = [
            '#type' => 'button',
            '#value' => t('Cancel'),
            '#attributes' => array('onClick' => 'history.go(-1); return true;'),
        ];

        return $form;
    }

    /**
     * This is contact form custom validation.
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // Add length validation to first name.
        $fname = $form_state->getValue('first_name');
        $lname = $form_state->getValue('last_name');
        $phone = $form_state->getValue('phone_no');

        /**
         * Add chacter and length validation for first name.
         */

        if (!preg_match('/^[a-zA-Z]{3,20}$/', $fname)) {
            $form_state->setErrorByName(
                'first_name',
                $this->t('First name must contain only character and it must be between 3-20 character in length.')
            );
        }

        /**
         * Add chacter and length validation for last name.
         */
        if (!preg_match('/^[a-zA-Z]{3,20}$/', $lname)) {
            $form_state->setErrorByName(
                'last_name',
                $this->t('Last name must contain only character and it must be between 3-20 character in length.')
            );
        }

        /**
         * Add email validation.
         */
        if (!filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
            $form_state->setErrorByName('email', $this->t('Please enter valid email address.'));
        }

        /**
         * Allowed olny numbers in phone no.
         */
        if (!intval($phone)) {
            $form_state->setErrorByName('phone_no', $this->t('Phone number contain only numbers.'));
        }
        /**
         * Add phone no lenght validation.
         */
        if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
            $form_state->setErrorByName('phone_no', $this->t('Phone numbers should contain 10 or 11 digits.'));
        }
    }

    /**
     * Save data into database custom table
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        db_insert('evolent_contact')
            ->fields(array(
                'first_name' => $form_state->getValue('first_name'),
                'last_name' => $form_state->getValue('last_name'),
                'email' => $form_state->getValue('email'),
                'phone_no' => $form_state->getValue('phone_no'),
                'status' => $form_state->getValue('status'),
                'last_updated' => date("Y-m-d H:i:s", time()),
            ))->execute();
        //clear views cache
        drupal_flush_all_caches();
        drupal_set_message("Contact information have been saved successfully.");
        $response = new RedirectResponse("../evolent_contact/list");
        $response->send();
    }

}
