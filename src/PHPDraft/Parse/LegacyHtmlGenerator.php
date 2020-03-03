<?php

/**
 * This file contains the LegacyHtmlGenerator.
 *
 * @package PHPDraft\Parse
 *
 * @author  Sean Molenaar<sean@seanmolenaar.eu>
 */

namespace PHPDraft\Parse;

use PHPDraft\Out\BaseTemplateGenerator;
use PHPDraft\Out\LegacyTemplateGenerator;

/**
 * Class LegacyHtmlGenerator.
 */
class LegacyHtmlGenerator extends BaseHtmlGenerator
{
    /**
     * Get the HTML representation of the JSON object.
     *
     * @param string      $template Type of template to display.
     * @param string|null $image    Image to use as a logo
     * @param string|null $css      CSS to load
     * @param string|null $js       JS to load
     *
     * @throws ExecutionException As a runtime exception
     *
     * @return BaseTemplateGenerator HTML template to display
     */
    public function get_html(string $template = 'default', ?string $image = null, ?string $css = null, ?string $js = null): BaseTemplateGenerator
    {
        $gen = new LegacyTemplateGenerator($template, $image);

        if (!empty($css)) {
            $gen->css[] = explode(',', $css);
        }

        if (!empty($js)) {
            $gen->js[] = explode(',', $js);
        }

        $gen->sorting = $this->sorting;

        $gen->get($this->object);

        return $gen;
    }
}