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

namespace Librinfo\EcommerceBundle\Dashboard;

use Blast\CoreBundle\Dashboard\AbstractDashboardBlock;
use Librinfo\EcommerceBundle\Dashboard\Stats\Sales;

class EcommerceDashboardBlock extends AbstractDashboardBlock
{
    /**
     * @var Sales
     */
    private $salesStats;

    public function handleParameters()
    {
        $fakeSalesData = $this->salesStats->getData();

        // dump($fakeSalesData);

        $this->templateParameters = [
            'test'            => true,
            'salesAmountData' => $fakeSalesData,
        ];
    }

    /**
     * @param Sales $salesStats
     */
    public function setSalesStats(Sales $salesStats): void
    {
        $this->salesStats = $salesStats;
    }
}
