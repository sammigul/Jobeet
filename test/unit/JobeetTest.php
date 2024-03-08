<?php 
// unit.php file is included to initialize a few things
require_once dirname(__FILE__).'/../bootstrap/unit.php';
 
$t = new lime_test(8);
 
$t->comment('::sluggify()');
$t->is(Jobeet::slugify('Sensio'), 'sensio','::sluggify() converts all chars to lowercase');
$t->is(Jobeet::slugify('sensio labs'), 'sensio-labs','::sluggify replaces whitespace by -');
$t->is(Jobeet::slugify('sensio   labs'), 'sensio-labs');
$t->is(Jobeet::slugify('paris,france'), 'paris-france');
$t->is(Jobeet::slugify('  sensio'), 'sensio');
$t->is(Jobeet::slugify('sensio  '), 'sensio');
$t->is(Jobeet::slugify(''), 'n-a','::sluggify() converts the empty string to n-a');
$t->is(Jobeet::slugify(' - '), 'n-a', '::slugify() converts a string that only contains non-ASCII characters to n-a');