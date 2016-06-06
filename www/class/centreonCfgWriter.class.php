<?php
/*
 * Copyright 2005-2015 Centreon
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give Centreon
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of Centreon choice, provided that
 * Centreon also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 */

/*
 *  Class that allows to write Nagios configuration files
 */
class CentreonCfgWriter
{
    private $buffer;
    private $fd;
    private $xmlBuffer;
    private $centreon;
    private $file_path;

    /**
     *  Constructor
     *
     *  @param Centreon $centreon
     *  @param string $file_full_path
     *  @return void
     */
    public function __construct($centreon, $file_full_path)
    {
        $this->centreon = $centreon;
        $this->buffer = "";
        $this->xmlBuffer = new CentreonXML();
        $this->file_path = $file_full_path;
        $this->fd = $this->createFile();
    }

    /**
     *  Creates the file
     *
     *  @return void
     */
    protected function createFile()
    {
        /*if (!$this->fd = fopen($this->file_path, 'w')) {
            throw new Exception(_("Could not create file") . " : " . $this->file_path);
        }*/
        $this->createFileHeader();
    }

    /**
     *  Writes basic text line to buffer
     *  Returns the length of written text
     *
     *  @param string $text
     *  @return string
     */
    protected function writeText($text)
    {
        $this->buffer .= $text;
        return (strlen($text));
    }

    /**
     *  Inserts Header of the file
     *
     *  @return void
     */
    protected function createFileHeader()
    {
        $time = date("F j, Y, g:i a");
        $by = $this->centreon->user->get_name();
        $len = $this->writeText("###################################################################\n");
        $this->writeText("#                                                                 #\n");
        $this->writeText("#                       GENERATED BY CENTREON                     #\n");
        $this->writeText("#                                                                 #\n");
        $this->writeText("#               Developped by :                                   #\n");
        $this->writeText("#                   - Julien Mathis                               #\n");
        $this->writeText("#                   - Romain Le Merlus                            #\n");
        $this->writeText("#                                                                 #\n");
        $this->writeText("#                           www.centreon.com                      #\n");
        $this->writeText("#                For information : contact@centreon.com           #\n");
        $this->writeText("###################################################################\n");
        $this->writeText("#                                                                 #\n");
        $this->writeText("#         Last modification " . $time);

        $margin = strlen($time);
        $margin = $len - 28 - $margin - 2;

        for ($i = 0; $i != $margin; $i++) {
            $this->writeText(" ");
        }

        $this->writeText("#\n");
        $this->writeText("#         By " . $by);
        $margin = $len - 13 - strlen($by) - 2;

        for ($i = 0; $i != $margin; $i++) {
            $this->writeText(" ");
        }
        $this->writeText("#\n");
        $this->writeText("#                                                                 #\n");
        $this->writeText("###################################################################\n\n");
    }

    /**
     *  Defines cfg type
     *
     *  @param string $type
     *  @return void
     */
    public function start_cfg($type)
    {
        $this->writeText("define " . $type . "{\n");
        $this->xmlBuffer->startElement($type);
    }

    /**
     *  Ends cfg
     *
     *  @return void
     */
    public function end_cfg()
    {
        $this->writeText("\t}\n\n");
        $this->xmlBuffer->endElement();
    }

    /**
     *  Sets attributes
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function attribute($key, $value)
    {
        $len = strlen($key);
        if ($len <= 9) {
            $this->writeText("\t" . $key . "\t\t\t\t" . $value . "\n");
        } elseif ($len > 9 && $len <= 18) {
            $this->writeText("\t" . $key . "\t\t\t" . $value . "\n");
        } elseif ($len >= 19 && $len <= 27) {
            $this->writeText("\t" . $key . "\t\t" . $value . "\n");
        } elseif ($len > 27) {
            $this->writeText("\t" . $key . "\t" . $value . "\n");
        }
        $this->xmlBuffer->writeElement($key, $value);
    }

    /**
     * Writes in file
     *
     * @return void
     */
    public function createCfgFile()
    {
        file_put_contents($this->file_path, $this->buffer);
        /*if (!(strlen($this->buffer)) || !(fwrite($this->fd, $this->buffer))) {
            throw new Exception(_("Could not write in file") . " : " . $this->file_path);
        }
        fclose($this->fd);*/
    }

    /**
     * Returns XML format
     *
     * @return CentreonXML
     */
    public function getXML()
    {
        return $this->xmlBuffer;
    }
}