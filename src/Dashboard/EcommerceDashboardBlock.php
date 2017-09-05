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

class EcommerceDashboardBlock extends AbstractDashboardBlock
{
public function handleParameters()
{
// @TODO: Call a stat manager to get real graph data
$fakeSalesData = [
['x' => '2017-01-01', 'y' => 6500],
['x' => '2017-02-01', 'y' => 5900],
['x' => '2017-03-01', 'y' => 8000],
['x' => '2017-04-01', 'y' => 8100],
['x' => '2017-05-01', 'y' => 9600],
['x' => '2017-06-01', 'y' => 12500],
['x' => '2017-07-01', 'y' => 14000],
];

$this->templateParameters = [
'test'            => true,
'salesAmountData' => $fakeSalesData,
];
}
}
