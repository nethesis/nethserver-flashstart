<?php
namespace NethServer\Module\FlashStart\External;

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

/**
 * Configure FlashStart profiles
 *
 * @author Giacomo Sanchietti <giacomo.sanchietti@nethesis.it>
 */
class Configure extends \Nethgui\Controller\Table\AbstractAction
{
    public function prepareView(\Nethgui\View\ViewInterface $view)
    {
         $password = '';
         $user = $this->getPlatform()->getDatabase('configuration')->getProp('nethupdate','SystemID');
         $interfaces = $this->getPlatform()->getDatabase('networks')->getAll();
         $bridge = false;
         foreach ($interfaces as $key => $props) {
             if ($props['role'] == 'green') {
                 if ($props['type'] == 'bridge') {
                     $bridge = $props['device'];
                 } else {
                      $password = $props['hwaddr'];
                      break;
                 }
             }
             if ($bridge) {
                 if ($props['role'] == 'bridged' && $props['bridge'] == $bridge) {
                     $password = $props['hwaddr'];
                     break;
                 }
             }

         }
         $password = strtolower(str_replace(':','',$password)); 
         $view['url'] = "http://cloud.flashstart.it/?x=login&autologinhy=1&u=$user&p=$password";
    }
}
