<?php
namespace Drupal\evolent_contact\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DeleteForm.
 *
 * @package Drupal\evolent_contact\Form
 */
class EvolentContactDeleteForm extends ConfirmFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'delete_form';
    }

    public function getQuestion()
    {
        return t('Do you want to delete this contact');
    }
    public function getCancelUrl()
    {
        return new Url('evolent_contact.list');
    }
    public function getDescription()
    {
        return t('Only do this if you are sure!');
    }
    /**
     * {@inheritdoc}
     */
    public function getConfirmText()
    {
        return t('Delete it!');
    }
    /**
     * {@inheritdoc}
     */
    public function getCancelText()
    {
        return t('Cancel');
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $cid = null)
    {
        $this->id = $cid;
        return parent::buildForm($form, $form_state);
    }
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $cid = $_GET['id'];
        $database = \Drupal::database();
        $res = db_delete('evolent_contact')
            ->condition('cid', $cid)
            ->execute();
        //clear views cache
        drupal_flush_all_caches();
        drupal_set_message("selected contact deleted succesfully.");
        $response = new RedirectResponse("../evolent_contact/list");
        $response->send();
    }
}
