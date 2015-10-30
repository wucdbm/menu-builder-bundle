<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Controller;

use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class DashboardController extends BaseController {

    public function dashboardAction() {
        return $this->render('@WucdbmMenuBuilder/Dashboard/dashboard.html.twig');
    }

}