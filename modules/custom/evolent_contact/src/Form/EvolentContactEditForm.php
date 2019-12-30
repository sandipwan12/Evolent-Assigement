<?php

namespace Drupal\evolent_contact\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * This is a simple form for managing contact details.
 * {@inheritdoc}
 */
class EvolentContactEditForm extends FormBase
{

    /**
     * Here we return formId to drupal core for understading.
     */
    public function getFormId()
    {
        return 'evolent_contact_edit_form';
    }

    /**
     * This actaully building form that are shown on front end.
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $cId = $_GET['id'];

        $database = \Drupal::database();
        $query = $database->query("SELECT * FROM {evolent_contact} where cid = $cId");
        $result = $query->fetchAssoc($cId);

        /*print($cId);
        echo $result['status'];
        print("<pre>".print_r($result,true)."</pre>"); die('stop'); */

        $form['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First name'),
            '#default_value' => $result['first_name'],
            '#required' => true,
            '#maxlength' => 20,
        ];

        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last name'),
            '#default_value' => $result['last_name'],
            '#required' => true,
            '#maxlength' => 20,
        ];
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#default_value' => $result['email'],
            '#required' => true,
            '#maxlength' => 100,
        ];
        $form['phone_no'] = [
            '#type' => 'tel',
            '#title' => $this->t('Phone no'),
            '#default_value' => $result['phone_no'],
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

        $form['cancel'] = array(
            '#type' => 'submit',
            //'#submit' => array('::previousForm'), //this works too
            '#submit' => array([$this, 'cancelForm']),
            '#value' => $this->t('Cancel'),
            '#limit_validation_errors' => array(), //no validation for back button
        );

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
     * Save data into database cuastom table
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $cId = $_GET['id'];
        db_update('evolent_contact')
            ->fields(array(
                'first_name' => $form_state->getValue('first_name'),
                'last_name' => $form_state->getValue('last_name'),
                'email' => $form_state->getValue('email'),
                'phone_no' => $form_state->getValue('phone_no'),
                'status' => $form_state->getValue('status'),
                'last_updated' => date("Y-m-d H:i:s", time()),
            ))
            ->condition('cid', $cId)
            ->execute();
        //clear views cache
        drupal_flush_all_caches();

        $response = new RedirectResponse("../evolent_contact/list");
        //return $response;
        $response->send();
        $this->messenger()->addStatus('Contact record have been updated successfully.', true);
    }

    public static function cancelForm(array &$form, FormStateInterface $form_state)
    {
        $response = new RedirectResponse("../evolent_contact/list");
        $response->send();
    }

}
