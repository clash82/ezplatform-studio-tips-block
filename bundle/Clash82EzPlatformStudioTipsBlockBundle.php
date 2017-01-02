<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Clash82\EzPlatformStudioTipsBlockBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Clash82\EzPlatformStudioTipsBlockBundle\DependencyInjection\EzPlatformStudioTipsBlockExtension;

class Clash82EzPlatformStudioTipsBlockBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new EzPlatformStudioTipsBlockExtension();
    }
}
