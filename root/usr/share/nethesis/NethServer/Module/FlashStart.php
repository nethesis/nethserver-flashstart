<?php
namespace NethServer\Module;

/*
 * Copyright (C) 2011 Nethesis S.r.l.
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
 * Implementation of FlashStart filters
 */
class FlashStart extends \Nethgui\Controller\AbstractController
{
    protected function initializeAttributes(\Nethgui\Module\ModuleAttributesInterface $base)
    {
        return \Nethgui\Module\SimpleModuleAttributesProvider::extendModuleAttributes($base, 'Gateway', 50);
    }

    private function initRoles()
    {
        $roles = array();
        foreach ($this->getPlatform()->getDatabase('networks')->getAll() as $key => $props) {
            if (isset($props['role']) && ($props['role'] == 'green' || $props['role'] == 'blue' || $props['role'] == 'hotspot')) {
                $roles[$props['role']] = '';
            }
        }
        $this->roles = array_keys($roles);
    }

    public function initialize()
    {
        parent::initialize();

        if (!$this->roles) {
            $this->initRoles();
        }
        $roleValidator = $this->createValidator()->collectionValidator($this->createValidator()->memberOf($this->roles));
        $this->declareParameter('status', Validate::SERVICESTATUS, array('configuration', 'flashstart', 'status'));
        $this->declareParameter('Username', Validate::EMAIL, array('configuration', 'flashstart', 'Username'));
        $this->declareParameter('Password', Validate::NOTEMPTY, array('configuration', 'flashstart', 'Password'));
        $this->declareParameter('Roles', $roleValidator, array('configuration', 'flashstart', 'Roles',','));
        $this->declareParameter('Bypass', Validate::ANYTHING, array('configuration', 'flashstart', 'Bypass'));
    }

    public function readBypass($v)
    {
        return implode("\n", explode(",", $v));
    }
    public function writeBypass($p)
    {
        return array(implode(',', array_filter(preg_split("/[,\s]+/", $p))));
    }


    public function prepareView(\Nethgui\View\ViewInterface $view)
    {
        parent::prepareView($view);
        if (!$this->roles) {
            $this->initRoles();
        }
        $user = $this->getPlatform()->getDatabase('configuration')->getProp('flashstart','Username');
        $pass = $this->getPlatform()->getDatabase('configuration')->getProp('flashstart','Password');
        if (!$user && !$password ) {
            $view['Registration'] = $view->translate('Before using FlashStart filter, register at http://flashstart.nethesis.it/ to obtain a user name and password.');
        }
        $view['FlashStartSite'] = "http://flashstart.nethesis.it/";
        $view['RolesDatasource'] = array_map(function ($x) use ($view) {
               return array($x, $view->translate($x."_label"));
        }, $this->roles);

    }

    private function validateFlashStartLogin($user, $pass) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://ddns.flashstart.it/nic/update?hostname=&myip=&wildcard=NOCHG&username=$user&password=$pass");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return ($res == "good");
    }

    public function validate(\Nethgui\Controller\ValidationReportInterface $report)
    {
        parent::validate($report);
        if (!$this->getRequest()->isMutation()) {
            return;
        }
        $ipValidator = $this->createValidator()->orValidator($this->createValidator()->cidrBlock(), $this->createValidator(Validate::IPv4));
        $ips = array_filter(preg_split('/[,\s]+/', $this->parameters['Bypass']));
        foreach ($ips as $ip){
            if( ! $ipValidator->evaluate($ip)) {
                $report->addValidationErrorMessage($this, 'Bypass', 'valid_bypass', array($ip));
            }
        }
        if (!$this->validateFlashStartLogin($this->parameters['Username'], $this->parameters['Password'])) {
            $report->addValidationErrorMessage($this, 'Password', "bad_login");
        }    
    }

    protected function onParametersSaved($changes)
    {
        $this->getPlatform()->signalEvent('nethserver-flashstart-save');
    }
}
