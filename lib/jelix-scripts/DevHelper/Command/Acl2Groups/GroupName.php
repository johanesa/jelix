<?php
/**
* @author      Laurent Jouanneau
* @contributor Julien Issler
* @contributor Loic Mathaud
* @copyright   2007-2016 Laurent Jouanneau
* @copyright   2008 Julien Issler
* @copyright   2008 Loic Mathaud
* @link        http://www.jelix.org
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/

namespace Jelix\DevHelper\Command\Acl2Groups;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GroupName  extends \Jelix\DevHelper\AbstractCommandForApp {

    protected function configure()
    {
        $this
            ->setName('acl2group:name')
            ->setDescription('Change the name of a group')
            ->setHelp('')
            ->addArgument(
                'group',
                InputArgument::REQUIRED,
                'the group id to change'
            )
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'the name of the group'
            )
        ;
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $group = $input->getArgument('group');
        $name = $input->getArgument('name');
        $id = $this->_getGrpId($input, true);

        $cnx = \jDb::getConnection('jacl2_profile');
        $sql="UPDATE ".$cnx->prefixTable('jacl2_group')
            ." SET name=".$cnx->quote($name)."  WHERE id_aclgrp=".$id;
        $cnx->exec($sql);

        if ($output->verbose()) {
            $output->writeln("Group '".$group."' is renamed to $name");
        }
    }
}