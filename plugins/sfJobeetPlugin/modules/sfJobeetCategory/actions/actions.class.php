<?php
class categoryActions extends sfActions
{
  public function sfJobeetExecuteShow(sfWebRequest $request)
  {
    $this->category = $this->getRoute()->getObject();
   
    $this->pager = new sfDoctrinePager(
      'JobeetJob',
      sfConfig::get('app_max_jobs_on_category')
    );
    $this->pager->setQuery($this->category->getActiveJobsQuery());
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }
}
