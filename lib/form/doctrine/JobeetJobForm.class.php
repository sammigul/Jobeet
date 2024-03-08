<?php

/**
 * JobeetJob form.
 *
 * @package    jobeet
 * @subpackage form
 * @author     Abdul Sammi Gul
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class JobeetJobForm extends BaseJobeetJobForm
{

  // unset fields to remove them form the  form
  // instead of unsetting fields we can also explicitly list the fields we want to use using the useFields() method
  public function configure()
  {
    $this->removeFields();


    //$this->validatorSchema['email'] = new sfValidatorEmail(); // not encouraged as replacing default validator because default validation rules introspected from the databas schema are lost

    $this->validatorSchema['email'] = new sfValidatorAnd(array(
      $this->validatorSchema['email'],
      new sfValidatorEmail(),
    ));

    // restricting types to choose from a set of options
        
    $this->widgetSchema['type'] = new sfWidgetFormChoice(array(
      'choices'  => Doctrine_Core::getTable('JobeetJob')->getTypes(),
      'expanded' => true,
    ));

    // Changing the validator to restrict the possible choices

    $this->validatorSchema['type'] = new sfValidatorChoice(array(
      'choices' => array_keys(Doctrine_Core::getTable('JobeetJob')->getTypes()),
    ));

    // label is generated automatically, we can change it using the label option as we have done below
 
    $this->widgetSchema['logo'] = new sfWidgetFormInputFile(array(
      'label' => 'Company logo',
    ));

    // can also change labels in batch using the setLabels() method of the widget array
    
    $this->widgetSchema->setLabels(array(
      'category_id'    => 'Category',
      'is_public'      => 'Public?',
      'how_to_apply'   => 'How to apply?',
    ));

    // changing the default validator for logo widget
    $this->validatorSchema['logo'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/jobs',
      'mime_types' => 'web_images',
    ));

    // defining a help message for the is_public col
    $this->widgetSchema->setHelp('is_public', 'Whether the job can also be published on affiliate websites or not.');
    $this->widgetSchema->setNameFormat('job[%s]');


   }

   protected function removeFields(){
    unset(
      $this['created_at'], $this['updated_at'],
      $this['expires_at'], $this['is_activated'],
      $this['token']
    );
   }
}
