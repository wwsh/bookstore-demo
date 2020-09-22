<?php

require './vendor/autoload.php';

use Gitonomy\Git\Repository;

$repository = new Repository('/git');

$log = $repository->getLog('master');

$array = $log->getRevisions()->getAsTextArray();

var_dump($array);

$branch = $repository->getReferences()->getBranch('momra');

echo $branch->getCommitHash();

//echo $repository->run('rev-list', ['HEAD']);
