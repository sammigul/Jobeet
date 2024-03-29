<?php

/**
 * job actions.
 *
 * @package    jobeet
 * @subpackage job
 * @author     Abdul Sammi Gul
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfJobeetJobActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if (!$request->getParameter('sf_culture'))
    {
      if ($this->getUser()->isFirstRequest())
      {
        $culture = $request->getPreferredCulture(array('en', 'fr'));
        $this->getUser()->setCulture($culture);
        $this->getUser()->isFirstRequest(false);
      }
      else
      {
        $culture = $this->getUser()->getCulture();
      }
   
      $this->redirect('localized_homepage');
    }
   
    $this->categories = Doctrine_Core::getTable('JobeetCategory')->getWithJobs();
  }

  public function executeShow(sfWebRequest $request)
  {
    // $this->job = Doctrine::getTable('JobeetJob')->find(array($request->getParameter('id')));
    // we can also retrieve an object from the route, when parsing an incoming request, the routing stores the matching route object for our use in the actions
    $this->job = $this->getRoute()->getObject();

    $this->getUser()->addJobToHistory($this->job);

  }

  public function executeNew(sfWebRequest $request)
  {
    // defining default values at the creation of the form, one way is to decalre values in the database schema, 
    // other is to (used here  ) pass a pre-modified job object to the form constructor
    $job = new JobeetJob();
    $job->setType('full-time');

    $this->form = new JobeetJobForm($job);

  }
   
  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new JobeetJobForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }
   
  public function executeEdit(sfWebRequest $request)
  {
    $job = $this->getRoute()->getObject();
    $this->forward404If($job->getIsActivated());
   
    $this->form = new JobeetJobForm($job);
  }
   
  public function executeUpdate(sfWebRequest $request)
  {
    $this->form = new JobeetJobForm($this->getRoute()->getObject());
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }
   
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
 
    $jobeet_job = $this->getRoute()->getObject();
    $jobeet_job->delete();
 
    $this->redirect('sfJobeetJob/index');
  }

  public function executePublish(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
   
    $job = $this->getRoute()->getObject();
    $job->publish();
   
    if ($cache = $this->getContext()->getViewCacheManager())
    {
      $cache->remove('sfJobeetJob/index?sf_culture=*');
      $cache->remove('sfJobeetCategory/show?id='.$job->getJobeetCategory()->getId());
    }
   
    $this->getUser()->setFlash('notice', sprintf('Your job is now online for %s days.', sfConfig::get('app_active_days')));
   
    $this->redirect($this->generateUrl('job_show_user', $job));
  }

  public function executeExtend(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
   
    $job = $this->getRoute()->getObject();
    $this->forward404Unless($job->extend());
   
    $this->getUser()->setFlash('notice', sprintf('Your job validity has been extended until %s.', $job->getDateTimeObject('expires_at')->format('m/d/Y')));
   
    $this->redirect('job_show_user', $job);
  }
   
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );
   
    if ($form->isValid())
    {
      $job = $form->save();
   
      $this->redirect('job_show', $job);
    }
  }

  // Search
  public function executeSearch(sfWebRequest $request)
  {
    $this->forwardUnless($query = $request->getParameter('query'), 'sfJobeetJob', 'index');
 
    $this->jobs = Doctrine_Core::getTable('JobeetJob') ->getForLuceneQuery($query);
 
    if ($request->isXmlHttpRequest())
    {
      if ('*' == $query || !$this->jobs)
      {
        return $this->renderText('No results.');
      }
 
      return $this->renderPartial('sfJobeetJob/list', array('jobs' => $this->jobs));
    }
  }
  
}
