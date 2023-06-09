<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\InstallationBundle;

use Symfony\Component\HttpFoundation\RequestStack;

class InstallToolUser
{
    private int $timeout = 300;

    /**
     * @internal Do not inherit from this class; decorate the "contao_installation.install_tool_user" service instead
     */
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function isAuthenticated(): bool
    {
        if (!$this->requestStack->getSession()->has('_auth_until') || $this->requestStack->getSession()->get('_auth_until') < time()) {
            return false;
        }

        // Update the expiration date
        $this->requestStack->getSession()->set('_auth_until', time() + $this->timeout);

        return true;
    }

    public function setAuthenticated(bool $authenticated): void
    {
        if (true === $authenticated) {
            $this->requestStack->getSession()->set('_auth_until', time() + $this->timeout);
        } else {
            $this->requestStack->getSession()->remove('_auth_until');
        }
    }
}
