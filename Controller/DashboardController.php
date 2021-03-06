<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Controller;

use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class DashboardController extends BaseController {

    public function dashboardAction() {
        return $this->render('@WucdbmMenuBuilder/Dashboard/dashboard.html.twig');
    }

}