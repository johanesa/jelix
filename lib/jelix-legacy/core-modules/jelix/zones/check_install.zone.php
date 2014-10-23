<?php
/**
* @package    jelix-modules
* @subpackage jelix
* @author     Bastien Jaillot
* @contributor Laurent Jouanneau, Julien Issler
* @copyright  2008 Bastien Jaillot
* @copyright  2009 Julien Issler
* @copyright 2012 Laurent Jouanneau
* @licence    http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

include (JELIX_LIB_PATH.'installer/jInstallChecker.class.php');

/**
 * a zone to display a default start page with results of the installation check
 * @package jelix
 */
class check_installZone extends jZone {

    protected $_tplname='check_install';

    protected function _prepareTpl() {
        $lang = jApp::config()->locale;
        if (!$this->param('no_lang_check')) {
            $locale = jLocale::getPreferedLocaleFromRequest();
            if (!$locale)
                $locale = 'en_US';
            jApp::config()->locale = $locale;
        }

        $messages = new \Jelix\Installer\Checker\Messages($lang);
        $reporter = new \Jelix\Installer\Reporter\HtmlBuffer($messages);
        $check = new jInstallCheck($reporter, $messages);
        $check->run();

        $this->_tpl->assign('wwwpath', jApp::wwwPath());
        $this->_tpl->assign('configpath', jApp::configPath());
        $this->_tpl->assign('check',$reporter->trace);
   }
}
