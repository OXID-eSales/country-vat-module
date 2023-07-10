<?php

namespace OxidProfessionalServices\CountryVatAdministration\Model;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ViewConfig;

class AjaxContainer {
    
    public string $encoded;

    public function __construct(public string $container, public array $data, public string $uri) {
        $this->encoded = json_encode($data);
    }

    public function getUri(ViewConfig $viewConfig): string {
        return $viewConfig->getAjaxLink() . $this->uri;
    }

    public static function getInstance(string $container, array $data, string $uri): static {
        return oxNew(static::class, $container, $data, $uri);
    }

    public static function buildFromColumns(array $columns): array {
        $data = [];
        foreach ($columns ?? [] as $key => $cols) {
            $field = $cols[0];
            $visible = (bool)$cols[2];
            $ident = (bool)$cols[4];
            $col = [
                'key' => "_$key",
                'ident' => $ident,
            ];
            if (!$ident) {
                $col['label'] = Registry::getLang()->translateString("GENERAL_AJAX_SORT_".strtoupper($field));
                $col['visible'] = $visible;
            }
            $data[] = $col;
        }
        return $data;
    }
}