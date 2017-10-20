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

namespace Librinfo\EcommerceBundle\Dashboard\Stats;

use Librinfo\EcommerceBundle\Entity\Order;
use Librinfo\EcommerceBundle\Entity\Payment;

class Sales extends AbstractStats
{
    public function getData(array $parameters = []): array
    {
        $orderTableName = $this->doctrine->getManager()->getClassMetadata(Order::class)->getTableName();
        $paymentTableName = $this->doctrine->getManager()->getClassMetadata(Payment::class)->getTableName();
        $orderState = Order::STATE_FULFILLED;

        $sql = '
            SELECT
                date_part(\'year\', p.updated_at) || \'-\' || date_part(\'month\', p.updated_at) || \'-01\' AS x,
                --date_part(\'year\', p.updated_at) || \'-\' || date_part(\'month\', p.updated_at) || \'-\' || date_part(\'day\', p.updated_at) AS x,
                sum(o.total) / 100 AS y
                --p.updated_at::date AS originalDate
            FROM
                ' . $orderTableName . ' o
            LEFT JOIN
                ' . $paymentTableName . ' p
                ON
                    p.order_id = o.id
            WHERE
                o.state = :orderState
                AND
                p.updated_at > date(\'now\') - interval \'1 year\'
            GROUP BY
                x
            ORDER BY
                x ASC
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('orderState', $orderState);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
