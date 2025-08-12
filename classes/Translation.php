<?php

namespace database;

class Translation
{
    /**
     * @var array <string, string[]>  $translations
     */
    private array $translations = [];

    public function __construct(private readonly string $language = 'de')
    {
        $translationFile = __DIR__."/../locales/translations.ini";
        if (file_exists($translationFile) && parse_ini_file($translationFile, true)) {
            $this->translations = parse_ini_file($translationFile, true);
        }
    }

    public function forKey(string $key): string
    {
        if ($this->translations[$this->language][$key]) {
            return $this->translations[$this->language][$key];
        }

        return "Translation not found";
    }
}

