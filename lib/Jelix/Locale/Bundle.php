<?php
/**
* @author     Laurent Jouanneau
* @author     Gerald Croes
* @copyright  2001-2005 CopixTeam, 2005-2016 Laurent Jouanneau
* Some parts of this file are took from Copix Framework v2.3dev20050901, CopixI18N.class.php, http://www.copix.org.
* copyrighted by CopixTeam and released under GNU Lesser General Public Licence.
* initial authors : Gerald Croes, Laurent Jouanneau.
* enhancement by Laurent Jouanneau for Jelix.
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
namespace Jelix\Locale;
use Jelix\Core\App;

/**
* a bundle contains all readed properties in a given language, and for all charsets
*/
class Bundle {

    public $fic;
    public $locale;

    protected $_loadedCharset = array ();
    protected $_strings = array();

    /**
    * constructor
    * @param jSelector   $file selector of a properties file
    * @param string      $locale    the code lang
    */
    public function __construct ($file, $locale){
        $this->fic  = $file;
        $this->locale = $locale;
    }

    /**
    * get the translation
    * @param string $key the locale key
    * @param string $charset
    * @return string the localized string
    */
    public function get ($key, $charset = null){

        if ($charset == null){
            $charset = App::config()->charset;
        }
        if (!in_array ($charset, $this->_loadedCharset)){
            $this->_loadLocales ($this->locale, $charset);
        }

        if (isset ($this->_strings[$charset][$key])){
            return $this->_strings[$charset][$key];
        }
        else {
            return null;
        }
    }

    /**
    * Loads the resources for a given locale/charset.
    * @param string $locale     the locale
    * @param string $charset    the charset
    */
    protected function _loadLocales ($locale, $charset){

        $this->_loadedCharset[] = $charset;

        $source = $this->fic->getPath();
        $cache = $this->fic->getCompiledFilePath();

        // check if we have a compiled version of the ressources

        if (is_readable ($cache)) {
            $okcompile = true;

            if (App::config()->compilation['force']){
               $okcompile = false;
            }
            else {
                if (App::config()->compilation['checkCacheFiletime']){
                    if (is_readable ($source) && filemtime($source) > filemtime($cache)){
                        $okcompile = false;
                    }
                }
            }

            if ($okcompile) {
                include ($cache);
                $this->_strings[$charset] = $_loaded;
                return;
            }
        }

        $reader = new \jPropertiesFileReader($source, $charset);
        $reader->parse();
        $this->_strings[$charset] = $reader->getProperties();
        $content = '<?php $_loaded= '.var_export($this->_strings[$charset], true).' ?>';
        \jFile::write($cache, $content);
    }
}
