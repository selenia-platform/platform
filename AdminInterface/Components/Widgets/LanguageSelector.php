<?php
namespace Selenia\Plugins\AdminInterface\Components\Widgets;

use Selenia\Localization\Services\Locale;
use Selenia\Matisse\Components\Base\CompositeComponent;

class LanguageSelector extends CompositeComponent
{
  /** @var Locale */
  public $locale;
  public $template = <<<'HTML'

    <ul class="LanguageSelector nav nav-pills bar">
      <Repeat for="{{ locale.getAvailableExt }}" as="i:loc">
        <Link id="btn-{{ loc.name }}"
              wrapper="li"
              script="selenia.setLang('{{ loc.name }}')"
              label="{{ loc.label }}"
              active="{{ !i }}"/>
      </Repeat>
      <li class="disabled"><a href="javascript:nop()">$LANGUAGE</a></li>
    </ul>

    <Script name="LanguageSelector">
      selenia.on ('languageChanged', function (lang) {
        $ ('.LanguageSelector li').removeClass ('active');
        $ ('#btn-' + lang).addClass ('active');
      }).setLang ('{{ locale.locale }}');
    </Script>

HTML;

  public function __construct (Locale $locale)
  {
    parent::__construct ();
    $this->locale = $locale;
  }

}