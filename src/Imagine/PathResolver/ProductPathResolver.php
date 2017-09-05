<?php

/*
* This file is part of the Blast Project package.
*
* Copyright (C) 2015-2017 Libre Informatique
*
* This file is licenced under the GNU LGPL v3.
* For the full copyright and license information, please view the LICENSE.md
* file that was distributed with this source code.
*/

namespace Librinfo\EcommerceBundle\Imagine\PathResolver;

use Librinfo\MediaBundle\Imagine\PathResolver\PathResolverInterface;
use Librinfo\MediaBundle\Imagine\PathResolver\DefaultResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Librinfo\MediaBundle\Entity\File;

class ProductPathResolver extends DefaultResolver implements PathResolverInterface
{
public function resolvePath($path)
{
try {
if (null === $this->cacheFile) {
/* @var $this->cacheFile File */
$this->cacheFile = $this->findFile($path);
}

$webFilePath = $this->webDir . '/' . $path;

if (!is_file($webFilePath)) {
$webFilePath = $this->webDir . '/bundles/librinfoecommerce/img/default-product-picture.png';
}

$fakeFile = new File();
$fakeFile->setFile(base64_encode(file_get_contents($webFilePath)));
$fakeFile->setMimeType(mime_content_type($webFilePath));

$this->cacheFile = $fakeFile;

return $fakeFile->getFile();

if (null === $this->cacheFile) {
throw new NotFoundHttpException(sprintf('File « %s » was not found', $path));
} else {
return $this->cacheFile->getFile();
}
} catch (\NotFoundHttpException $e) {
throw new NotFoundHttpException(sprintf('File « %s » was not found', $path));
}
}

protected function findFile($path)
{
$file = null;

$qb = $this->em->createQueryBuilder();
$subQb = $this->em->createQueryBuilder();

$subQb
->select('f')
->from('LibrinfoEcommerceBundle:ProductImage', 'pi')
->join('LibrinfoMediaBundle:File', 'f', 'WITH', 'pi.realFile = f')
->where('pi.path = :path')
->setParameter('path', $path);

$file = $subQb->getQuery()->getOneOrNullResult();

if (!$file) {
$qb
->select('f')
->from('LibrinfoMediaBundle:File', 'f')
->where('f.path = :path')
->setParameter('path', $path);

$file = $qb->getQuery()->getOneOrNullResult();
}

return $file;
}
}
