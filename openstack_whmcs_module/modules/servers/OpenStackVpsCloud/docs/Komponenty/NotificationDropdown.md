#### Użycie

Żeby użyć komponentu w swoim projekcie należy stworzyć swoją klasę i dziedziczyć po komponencie, pamiętając o interfacach

![image](uploads/dcbaf81a1dfbe2c94d38bfbd27ed3c88/image.png)

Następnie należy nadpisać metodę `loadItems()`

![image](uploads/3f24c63629521a995afdfcb15a4f401a/image.png)

Komponent dodatkowo udostępnia dwa callbacki na clicki itemów / przycisków:

`clickItemCallback($itemId)` oraz `deleteItemCallback($itemId)`


#### Autoload
Komponent ma możliwość cyklicznego reloadu (odpalenia metody loadItems())<br>
Żeby go włączyć odpalamy metodę `enableAutoReload()` np. w konstruktorze<br>
Dodatkowo możemy zdefiniować interwał w milisekundach metodą `setReloadInterval(int $milliseconds)`

Ważne: <br>
Komponent domyślnie ma **wyłączony autoreload** a po włączeniu interwał ma domyślną wartość rzędu **30000ms** = 30s

Przykład użycie komponentu jest w Samplach