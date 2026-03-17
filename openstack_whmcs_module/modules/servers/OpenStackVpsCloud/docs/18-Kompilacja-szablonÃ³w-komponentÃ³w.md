### Co to

Jest to proces polegający na przerobieniu i umieszczeniu wszystkich templatek - plików components.html - do jednego zbiorczego pliku JS. Czyli inaczej skompilowaniu ich.

### Po co

Zabieg ten ma na celu uniknięcie blokowania kontentu przez CSP (Content Security Policy). 
Jednym ze sposobów jest właśnie umieszczenie HTMLa ładowanego przez Vue do jednego pliku JS.

[https://developer.mozilla.org/en-US/docs/Web/HTTP/Guides/CSP](https://vuejsdevelopers.com/2017/05/08/vue-js-chrome-extension/)

### Jak to zrobić

FW udostępnia Kompilator http://git.mglocal/whmcs-products/module-framework/-/blob/staging-i1193/core/Components/Compiler/ComponentsTemplatesCompiler.php?ref_type=heads <br> który możemy odpalić za pomocą komendy `build:assets` <br> http://git.mglocal/whmcs-products/module-framework/-/blob/staging-i1193/core/Cron/BuildAssets.php?ref_type=heads. <br>
Jej zadaniem jest załadowanie odpowiednich bibliotek `vue-template-compiler` oraz odpalenie skryptu do kompilacji http://git.mglocal/whmcs-products/module-framework/-/blob/staging-i1193/resources/utilities/tools/components-templates-compiler.js?ref_type=heads.

Efektem jest wynikowy plik umieszczony w `/resources/utilities/source/compiled-components-templates-<versia_modułu>.js` . Ten plik jest ładowany w głównym HTMLu modułu i na jego podstawie są rejestrowane komponenty do VUE.

Dodatkowo żeby tryb takiej prekompilacji zadziałał należy w konfiguracji modułu (configuration.yml) ustawić flagę `precompiledMode` na **true**

Od tej pory CSP nie powinno się czepiać naszych modułów.