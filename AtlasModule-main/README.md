# AtlasModule
Como instalar:

Nescessario no VPS DragonCoreSSH 6 BETA ou Superior!
Caso não contenha use o comando abaixo:
```sh
bash <(wget -qO- https://raw.githubusercontent.com/Penguinehis/DragonCoreSSH-Beta/main/install.sh)
```

Instale o painel web Atlas normalmente, e sincronize sua VPS

Apos sincronizar sua VPS use o comando abaixo na VPS para substituir os modulos pelos modulos Dragon


```sh
rm atlasdata.sh || true && rm atlascreate.sh || true && rm atlasteste.sh || true && rm atlasremove.sh || true && rm delete.py || true && rm sincronizar.py || true && wget https://raw.githubusercontent.com/Penguinehis/AtlasModule/main/atlascreate.sh && chmod 777 atlascreate.sh && wget https://raw.githubusercontent.com/Penguinehis/AtlasModule/main/atlasteste.sh && chmod 777 atlasteste.sh && wget https://raw.githubusercontent.com/Penguinehis/AtlasModule/main/atlasremove.sh && chmod 777 atlasremove.sh && wget https://raw.githubusercontent.com/Penguinehis/AtlasModule/main/delete.py && wget https://raw.githubusercontent.com/Penguinehis/AtlasModule/main/atlasdata.sh && chmod 777 atlasdata.sh && chmod 777 delete.py && wget https://raw.githubusercontent.com/Penguinehis/AtlasModule/main/sincronizar.py && chmod 777 sincronizar.py > /dev/null 2>&1
```