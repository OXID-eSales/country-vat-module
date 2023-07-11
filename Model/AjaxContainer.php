<?php

namespace OxidProfessionalServices\CountryVatAdministration\Model;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ViewConfig;

/**
 * Helper class to manage ajax containers for drag & drop views.
 */
class AjaxContainer
{
    /**
     * @var string json encoded data
     */
    public string $encoded;

    /**
     * @param string $container container index defined in _aColumns
     * @param array  $data      formated content {@see AjaxContainer::buildFromColumns()}
     * @param string $uri       ajax uri to call for each container
     */
    public function __construct(public string $container, public array $data, public string $uri)
    {
        $this->encoded = json_encode($data);
    }

    /**
     * @return string the ajax uri for a given container, concated with {@see AjaxContainer::$uri}
     */
    public function getUri(ViewConfig $viewConfig): string
    {
        return $viewConfig->getAjaxLink() . $this->uri;
    }

    public static function getInstance(string $container, array $data, string $uri): static
    {
        return oxNew(static::class, $container, $data, $uri);
    }

    /**
     * @param array<string, bool|int|string> $columns defined in ajax controller
     *
     * @return array<string, bool|string>
     */
    public static function buildFromColumns(array $columns): array
    {
        $data = [];
        foreach ($columns ?? [] as $key => $cols) {
            $field   = $cols[0];
            $visible = (bool) $cols[2];
            $ident   = (bool) $cols[4];
            $col     = [
                'key'   => "_{$key}",
                'ident' => $ident,
            ];
            if (!$ident) {
                $col['label']   = Registry::getLang()->translateString('GENERAL_AJAX_SORT_' . strtoupper($field));
                $col['visible'] = $visible;
            }
            $data[] = $col;
        }

        return $data;
    }
}
