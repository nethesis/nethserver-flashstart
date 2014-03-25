<?php
namespace NethServer\Module\FlashStart\UserProfile;

/*
 * Copyright (C) 2012 Nethesis S.r.l.
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
use Nethgui\Controller\Table\Modify as Table;

/**
 * Modify user profile
 *
 * @author Giacomo Sanchietti <giacomo.sanchietti@nethesis.it>
 */
class Modify extends \Nethgui\Controller\Table\Modify
{
    private $ports = array( '53', '5402', '5403', '5353', '110', '143');

    private function getFreePort()
    {
        $free_ports = $this->ports;
        $keys = $this->getPlatform()->getDatabase('flashstart')->getAll();
        foreach ($keys as $key => $props) {
            $free_ports = array_diff($free_ports, array($props['Port']));
        }
        return array_shift($free_ports);
    }


    public function initialize()
    {
        $parameterSchema = array(
            array('name', Validate::USERNAME, \Nethgui\Controller\Table\Modify::KEY),
            array('Port', $this->createValidator()->memberOf($this->ports), \Nethgui\Controller\Table\Modify::FIELD),
            array('Description', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD),
            array('Group', Validate::ANYTHING, \Nethgui\Controller\Table\Modify::FIELD),
        );

        $this->setSchema($parameterSchema);

        parent::initialize();
    }

    public function prepareView(\Nethgui\View\ViewInterface $view)
    {
        parent::prepareView($view);
        $templates = array(
            'create' => 'NethServer\Template\FlashStart\Modify',
            'update' => 'NethServer\Template\FlashStart\Modify',
            'delete' => 'Nethgui\Template\Table\Delete',
        );
        $view->setTemplate($templates[$this->getIdentifier()]);

        $account_group = $this->getPlatform()->getDatabase('accounts')->getAll('group');

        $tmp = array();
        foreach($account_group as $key => $props) {
            $tmp[] = array($key, sprintf("%s (%s)",$props['Description'],$key));
        }

        $view['GroupDatasource'] = $tmp;
    }

    public function process()
    {
        if ($this->getIdentifier() === 'create' && $this->getRequest()->isMutation()) {
            $port = $this->getFreePort();
            $this->getPlatform()->getDatabase('flashstart')->setKey($this->parameters['Group'], 'user-profile',
                    array('Port' => $port, 'Description' => $this->parameters['Description'])
            );
        }

        if($this->getRequest()->isMutation()) {
            $this->exitCode = $this->getPlatform()->signalEvent('nethserver-flashstart-save')->getExitCode();
        }
    }

    public function nextPath()
    {
        // Workaround for LazyLoaderAdapter to reload table contents after mutation request
        if($this->getRequest()->isMutation()) {
            return '/FlashStart/UserProfile/read';
        }
        return parent::nextPath();
    }


}
