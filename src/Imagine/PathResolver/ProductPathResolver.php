<?php

namespace Librinfo\EcommerceBundle\Imagine\PathResolver;

use Librinfo\MediaBundle\Imagine\PathResolver\PathResolverInterface;
use Librinfo\MediaBundle\Imagine\PathResolver\DefaultResolver;

class ProductPathResolver extends DefaultResolver implements PathResolverInterface
{

    public function resolvePath($path)
    {
        try {
            $repo = $this->em->getRepository('LibrinfoMediaBundle:File');

            if (!$this->cacheFile) {
                /** @var $this->cacheFile File */
                $this->cacheFile = $repo->findOneBy(['path'=>$path]);
            }

            return $this->cacheFile->getFile();
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('File « %s » was not found', $path));
        }
    }

}
