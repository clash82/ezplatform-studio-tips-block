<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace OmniProject\EzStudioTipsBlockBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use OmniProject\EzStudioTipsBlockBundle\DependencyInjection\EzStudioTipsBlockExtension;

class OmniProjectEzStudioTipsBlockBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new EzStudioTipsBlockExtension();
    }
}
