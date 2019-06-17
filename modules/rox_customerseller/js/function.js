/*
* 2015 David Mrózek
*
* Licenční podmínky použití produktů
*
* Při zakoupení produktu (modulu) se sjednává licence na jeden produkční web / eshop / doménu.
* 
* Produkty (moduly) lze provozovat i na testovací instalaci na PC vlastníka licence.
* 
* Licence není časově omezena, je sjednána na dobu neurčitou.
* 
* Autorská práva a jiná práva duševního vlastnictví, vztahující se k dodávaným
* produktům (modulům), náleží dodavateli jakožto tvůrci dodávaného produktu.
* 
* Autorská práva se řídí právními předpisy České republiky.
* 
* Za řádně a v plné výši uhrazený produkt (modul) dle sjednaných podmínek uděluje
* dodavatel odběrateli užívat licenci výhradně k jeho účelům dle obchodních a licenčních podmínek.
* 
* Odběratel není oprávněn užívat větší množství licencí, než jaký byl dodavatelem poskytnut nebo byl sjednán při zakoupení produktu.
* 
* V ceně licence nejsou zahrnuty žádné další dodatečné individuální úpravy, dodatečné
* služby spojené s instalací a provozem produktu (modulu) ani aktualizace modulů (pokud autor sám nenabídne).
* 
* Odběratel není oprávněn produkt (modul) nabízet, poskytovat, prodávat, vystavovat
* ke stažení, zprostředkovávat další prodej, kopírovat, množit. Odběratel nesmí produkt
* (modul) poskytnout třetím stranám ani zdarma, ani za finanční úhradu, ani si účtovat za jejich instalaci a spojené služby.
*
* V případě porušení licenčních ujednání popsaných výše, může dodavatel požadovat finanční náhradu v případě ušlého zisku.
* 
* Dodaný software jako autorské dílo požívá ochrany zejména zák. č. 121/2000 Sb. autorského zákona a zák. č. 140/1961 Sb., trestního zákona.
* 
* Odběratel je oprávněn jej užít pouze v rozsahu a způsobem stanoveným těmito obchodními podmínkami.
* 
* Dodavatel má nárok dohlížet na dodržování licenčních a autorských práv.
* 
* V případě zásahu odběratele do autorských práv dodavatele vzniká dodavateli nárok
* na smluvní pokutu ve výši 50 000 Kč. Smluvní pokuta je splatná na základě výzvy k úhradě
* smluvní pokuty ve lhůtě 15 dnů od doručení výzvy. Nárok dodavatele na náhradu škody
* způsobené zásahem odběratele do autorských práv dodavatele, není zaplacením smluvní pokuty odběratelem dotčen.
* 
* Vedle nároku na smluvní pokutu má dodavatel v případě zásahu odběratele do jeho autorských
* práv nároky vyplývající z autorského zákona, zejména nárok na zdržení se dalších zásahů
* do autorských práv, nárok na sdělení údajů o způsobu a rozsahu neoprávněného užití
* software a nárok na odstranění následků zásahu do autorských práv včetně poskytnutí
* přiměřeného zadostiučinění a vydání případného bezdůvodného obohacení.
*
*  @author David Mrózek <admin@valasinec.cz>
*  @copyright  2015 David Mrózek
*  
*/

function show_tab(num, all)
{
    for(var i=1;i<=all;i++)
  	{
        // Zruším aktivní záložky
      	document.getElementById("li-"+i).className = "";
        // Skryji všechny záložky
      	document.getElementById("tab-"+i).className = "hide";
  	}
    
    // Nastavím vybranou záložku na ACTIVE
    document.getElementById("li-" + num).className = "active";
    // Nastavím vybranou záložku na viditelnou
    document.getElementById("tab-" + num).className = "show";
    
    return false;
}

// Funkce na změnu třídy - Zobrazit / Skrýt
function zmenaTridy(element, prvniTrida, druhaTrida)
{
	 element.className = element.className == prvniTrida ? druhaTrida : prvniTrida;
}
