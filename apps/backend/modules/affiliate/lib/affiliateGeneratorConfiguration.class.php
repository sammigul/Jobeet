<?php

/**
 * affiliate module configuration.
 *
 * @package    jobeet
 * @subpackage affiliate
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class affiliateGeneratorConfiguration extends BaseAffiliateGeneratorConfiguration
{
    public function getFilteDefaults()
    {
        return array('is_active' => 0);
    }
}
