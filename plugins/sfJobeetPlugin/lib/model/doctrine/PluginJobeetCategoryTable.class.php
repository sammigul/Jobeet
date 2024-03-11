<?php

abstract class PluginJobeetCategoryTable extends Doctrine_Table
{
    public function getWithJobs()
    {
      $q = $this->createQuery('c')
        ->leftJoin('c.JobeetJobs j') // typo in the tutorial 
        ->where('j.expires_at > ?', date('Y-m-d H:i:s', time()));
      
      $q->andWhere('j.is_activated = ?', 1);
      return $q->execute();
    }

 


    public function doSelectForSlug($parameters)
    {
      return $this->findOneBySlugAndCulture($parameters['slug'], $parameters['sf_culture']);
    }

    public function findOneBySlugAndCulture($slug, $culture = 'en')
    {
      $q = $this->createQuery('a')
        ->leftJoin('a.Translation t')
        ->andWhere('t.lang = ?', $culture)
        ->andWhere('t.slug = ?', $slug);
      return $q->fetchOne();
    }

       /**
     * Returns a category based on its slug
     * @param string $slug
     * @return JobeetCategory
     */

     //
    public function findOneBySlug($slug)
    {
      return $this->findOneBySlugAndCulture($slug, 'en');
    }
}