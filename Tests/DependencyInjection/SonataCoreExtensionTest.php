<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\CoreBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sonata\CoreBundle\DependencyInjection\SonataCoreExtension;

class SonataCoreExtensionTest extends AbstractExtensionTestCase
{
    public function testAfterLoadingTheWrappingParameterIsSet()
    {
        $this->load();
        $this->assertContainerBuilderHasParameter(
            'sonata.core.form_type'
        );
        $this->assertSame(
            'standard',
            $this->container->getParameter(
                'sonata.core.form_type'
            )
        );
    }

    public function testHorizontalFormTypeMeansNoWrapping()
    {
        $this->load(['form_type' => 'horizontal']);
        $this->assertContainerBuilderHasParameter(
            'sonata.core.form_type'
        );
        $this->assertSame(
            'horizontal',
            $this->container->getParameter(
                'sonata.core.form_type'
            )
        );
    }

    public function testPrepend()
    {
        $containerBuilder = $this->prophesize(
            'Symfony\Component\DependencyInjection\ContainerBuilder'
        );

        $containerBuilder->getExtensionConfig('sonata_admin')->willReturn([
            ['some_key_we_do_not_care_about' => 42],
            ['options' => ['form_type' => 'standard']],
            ['options' => ['form_type' => 'horizontal']],
        ]);

        $containerBuilder->prependExtensionConfig(
            'sonata_core',
            ['form_type' => 'standard']
        )->shouldBeCalled();

        $containerBuilder->prependExtensionConfig(
            'sonata_core',
            ['form_type' => 'horizontal']
        )->shouldBeCalled();

        $extension = new SonataCoreExtension();
        $extension->prepend($containerBuilder->reveal());
    }

    protected function getContainerExtensions()
    {
        return [
            new SonataCoreExtension(),
        ];
    }
}
