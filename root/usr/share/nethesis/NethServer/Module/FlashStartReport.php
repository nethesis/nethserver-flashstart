<?php

namespace NethServer\Module;

/*
 * Copyright (C) 2017Nethesis S.r.l.
 * 
 * This script is part of NethServer.
 * 
 * NethServer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * NethServer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with NethServer.  If not, see <http://www.gnu.org/licenses/>.
 */

use Nethgui\System\PlatformInterface as Validate;

/**
 * Flashstart report
 *
 * @author Giacomo Sanchietti<giacomo.sanchietti@nethesis.it>
 */
class FlashStartReport extends \Nethgui\Controller\AbstractController
{

    protected function initializeAttributes(\Nethgui\Module\ModuleAttributesInterface $attributes)
    {
        return new \NethServer\Tool\CustomModuleAttributesProvider($attributes, array(
            'languageCatalog' => array('NethServer_Module_FlashStart'),
            'category' => 'Report')
        );
    }


    public function prepareView(\Nethgui\View\ViewInterface $view)
    {
        parent::prepareView($view);
        $report = json_decode($this->getPlatform()->exec('timeout 2 sudo /usr/libexec/nethserver/nethserver-flashstart-report')->getOutput(), true);
        if ($report) {
             $view['report'] = $report;
        } else {
             $view['report'] = array();
        }
    }
}
