<?php
/**
 * This file contains the LegacyDrafter.php.
 *
 * @package PHPDraft\Parse
 *
 * @author  Sean Molenaar<sean@seanmolenaar.eu>
 */

namespace PHPDraft\Parse;

class LegacyDrafter extends BaseParser
{
    /**
     * The location of the drafter executable.
     *
     * @var string
     */
    protected $drafter;

    /**
     * ApibToJson constructor.
     *
     * @param string $apib API Blueprint text
     *
     * @return \PHPDraft\Parse\Drafter
     */
    public function init(string $apib): BaseParser
    {
        parent::init($apib);
        $this->drafter = self::location();

        return $this;
    }

    /**
     * Return drafter location if found.
     *
     * @return bool|string
     */
    public static function location()
    {
        $returnVal = shell_exec('which drafter 2> /dev/null');
        $returnVal = preg_replace('/^\s+|\n|\r|\s+$/m', '', $returnVal);

        return empty($returnVal) ? FALSE : $returnVal;
    }

    /**
     * Check if a given parser is available.
     *
     * @return bool
     */
    public static function available(): bool
    {
        $path = self::location();

        $version = shell_exec('drafter -v 2> /dev/null');
        $version = preg_match('/^v3/', $version);

        return $path && $version === 1;
    }

    /**
     * Parses the apib for the selected method.
     *
     * @return void
     */
    protected function parse(): void
    {
        shell_exec($this->drafter . ' ' . $this->tmp_dir . '/index.apib -f json -o ' . $this->tmp_dir . '/index.json 2> /dev/null');
        $this->json = json_decode(file_get_contents($this->tmp_dir . '/index.json'));
    }
}
