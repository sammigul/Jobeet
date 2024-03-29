<?php

/**
 * JobeetAffiliate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    jobeet
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginJobeetAffiliate extends BaseJobeetAffiliate
{
    public function __toString()
    {
        return $this->getUrl();
    }

    // Generating token 
    public function save(Doctrine_Connection $conn = null)
    {
      if (!$this->getToken())
      {
        $this->setToken(sha1($this->getEmail().rand(11111, 99999)));
      }
   
      return parent::save($conn);
    }
    
    public function getActiveJobs()
    {
      $q = Doctrine_Query::create()
        ->select('j.*')
        ->from('JobeetJob j')
        ->leftJoin('j.JobeetCategory c')
        ->leftJoin('c.JobeetAffiliates a')
        ->where('a.id = ?', $this->getId());
   
      $q = Doctrine_Core::getTable('JobeetJob')->addActiveJobsQuery($q);
   
      return $q->execute();
    }

    public function activate(){
      $this->setis_active(true);
      return $this->save();
    }

    public function deactivate(){
      $this->setis_active(false);
      return $this->save();
    }
}